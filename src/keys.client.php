<?php
# Dependencias
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

# Variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$RABBITMQ_DEFAULT_USER = $_ENV['RABBITMQ_DEFAULT_USER'];
$RABBITMQ_DEFAULT_PASS = $_ENV['RABBITMQ_DEFAULT_PASS'];
$RABBITMQ_HOST = $_ENV['RABBITMQ_HOST'];
$RABBITMQ_PORT = $_ENV['RABBITMQ_PORT'];

// Creamos una conexión al servidor AMQP
$connection = new AMQPStreamConnection('host.docker.internal', 5672, $RABBITMQ_DEFAULT_USER, '[R6mF+wkA^9Re)');

/**
 * Abrimos un canal del servidor AMQP 
 * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html
 */
$channel = $connection->channel();

// Parametros
$procedure = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'private';
$num = implode(' ', array_slice($argv, 2));
if (empty($num)) {
    $num = "1";    
}
$corr_id = uniqid();
$response = null;

/**
 * > Exchanges are AMQP 0-9-1 entities where messages are sent to. Exchanges take a message and route it 
 * into zero or more queues. The routing algorithm used depends on the exchange type and rules called 
 * bindings.
 */
$exchangeName = 'keys_ms_call_exchange';
/**
 * Se declara un `exchange`
 * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_exchange_declare
 */
$channel->exchange_declare($exchangeName, 'direct', false, false, false);

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
list($reply_queue, ,) = $channel->queue_declare(
    "keys_ms_reṕly_queue",
    false,
    false,
    true, //exclusive
    false
);

echo " [x] Llamando keys.$procedure con $num. exchange: $exchangeName".PHP_EOL;

/**
 * 
 */
$msg = new AMQPMessage(
    (string) $num,
    array(
        'correlation_id' => $corr_id,
        'reply_to' => $reply_queue
    )
);

/**
 * http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_publish
 */
$channel->basic_publish($msg, $exchangeName, $procedure);

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
$channel->basic_consume(
    $reply_queue, //nombre
    '', //consumer_tag
    false, 
    true, //no_ack
    false,
    false,
    /**
     * @param {*} rep 
     */
    function ($rep) {
        if ($rep->get('correlation_id') == $GLOBALS["corr_id"]) {
            echo ' [.] Respuesta del servidor:'.PHP_EOL;
            echo $rep->body.PHP_EOL;
            $GLOBALS["response"] = $rep->body;
        }
    }
);

while (!$response) {
    $channel->wait();
}

$channel->close();
$connection->close();