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
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('mq', 5672, 'myuser', 'mypassword');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
?>