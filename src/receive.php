<?php
/**
* Este programa consume (recibe) el mensaje de MQ
* https://www.rabbitmq.com/tutorials/tutorial-one-php.html
* 
* PhpAmqpLib
* https://github.com/php-amqplib/php-amqplib
* 
*/
require __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('mq', 5672, 'myuser', 'mypassword');
$channel = $connection->channel();

$channel->queue_declare('signing_ms', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('signing_ms', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>