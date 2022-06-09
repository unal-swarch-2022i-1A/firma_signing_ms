<?php
namespace App;
# Dependencias
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Dotenv;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
class KeysRPCClient {

    private $onnection;
    private $channel;
    private $reply_queue;
    private $response;
    private $corr_id;
    private $exchangeName;

    /**
     * Definimos la conexión, el canal, la cola de respuesta y dejamos consumiendo la cola de respuesta
     */
    public function __construct()
    {
        # Variables de entorno

        $RABBITMQ_DEFAULT_USER = $_ENV['RABBITMQ_DEFAULT_USER'];
        $RABBITMQ_DEFAULT_PASS = $_ENV['RABBITMQ_DEFAULT_PASS'];
        $RABBITMQ_HOST = $_ENV['RABBITMQ_HOST'];
        $RABBITMQ_PORT = $_ENV['RABBITMQ_PORT'];
        
        // Creamos una conexión al servidor AMQP
        $this->connection = new AMQPStreamConnection(
            $RABBITMQ_HOST, 
            $RABBITMQ_PORT, 
            $RABBITMQ_DEFAULT_USER, 
            $RABBITMQ_DEFAULT_PASS
        );

        /**
         * Abrimos un canal del servidor AMQP 
         * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html
         */
        $this->channel = $this->connection->channel();

        /**
         * > Exchanges are AMQP 0-9-1 entities where messages are sent to. Exchanges take a message and route it 
         * into zero or more queues. The routing algorithm used depends on the exchange type and rules called 
         * bindings.
         */
        $this->exchangeName = 'keys_ms_call_exchange';
        /**
         * Se declara un `exchange`
         * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_exchange_declare
         */
        $this->channel->exchange_declare($this->exchangeName, 'direct', false, false, false);

        /**
         * Declaramos un cola exclusivamente para las respuestas del servidor a este cliente.
         * **Importante:** Necesitamos crear primero la cola de respuesta antes de publicar el la solicitud, porque en la solicitud 
         * indicamos cual es la cola donde se colocará las respuesta. Por eso primero se crear la cola de respuesta
         * y ahí si se publica la solicitud
         * @param nombre
         * @param passive
         * @param durable
         * @param exclusive
         * @param auto_delete
         * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_queue_declare
         */
        list($this->reply_queue, ,) = $this->channel->queue_declare(
            "keys_ms_reṕly_queue",
            false,
            false,
            true, //exclusive
            false
        );

        echo ' [*] Esperando respuesta...'.PHP_EOL;

        /**
         * Consume la cola de respuestas del servidor a este proceso cliente
         * > This method asks the server to start a "consumer", which is a transient request for messages from a specific queue. Consumers last as long as the channel they were declared on, or until the client cancels them.
         * @param nombre
         * @param consumer_tag
         * @param no_local
         * @param no_ack
         * @param exclusive
         * @param nowait
         * @param callback
         * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_consume
         */
        $this->channel->basic_consume(
            $this->reply_queue, //nombre
            '', //consumer_tag
            false, 
            true, //no_ack
            false,
            false,
            /**
             * @param {*} rep 
             */
            function ($rep) {
                if ($rep->get('correlation_id') == $this->corr_id ) {
                    $this->response = $rep->body;
                }
            }
        );        
    }

    public function run($procedure,$num)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        echo " [x] Llamando keys.$procedure con $num. exchange: $this->exchangeName".PHP_EOL;

        /**
         * 
         */
        $msg = new AMQPMessage(
            (string) $num,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->reply_queue
            )
        );

        /**
         * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_publish
         */
        $this->channel->basic_publish($msg, $this->exchangeName, $procedure);

        while (!$this->response) {
            $this->channel->wait();
        }

        return $this->response;
    }    
}

// Parametros
$procedure = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'private';
$num = implode(' ', array_slice($argv, 2));
if (empty($num)) {
    $num = "1";    
}

$keysRPCClient = new KeysRPCClient();
$response = $keysRPCClient->run($procedure,$num);
echo ' [.] Respuesta del servidor:'.PHP_EOL;
echo $response,PHP_EOL; 