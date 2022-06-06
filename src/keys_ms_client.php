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


$connection = new AMQPStreamConnection('host.docker.internal', 5672, $RABBITMQ_DEFAULT_USER, '[R6mF+wkA^9Re)');
$channel = $connection->channel();

$channel->exchange_declare('direct_logs', 'direct', false, false, false);

$severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'private';

$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = "1";
}

$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'direct_logs', $severity);

echo ' [x] Sent ', $severity, ':', $data, "\n";

$channel->close();
$connection->close();