<?php
header('Content-Type: application/json; charset=utf-8');
/**
 * https://phpseclib.com/docs/rsa
 */
require __DIR__ . '/../vendor/autoload.php';
require './sign.php';

// Extraer datos del body request
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
if (!isset($input['user_id']) || !isset($input['user_id'])) {
    http_response_code(400);
    die();
}

$user_id = (isset($input['user_id'])) ? $input['user_id'] : null;
$data = (isset($input['data'])) ? $input['data'] : null;
$obj = sign($user_id,$data );

// Salida del body response en JSON
$myJSON = json_encode($obj);
echo $myJSON;
