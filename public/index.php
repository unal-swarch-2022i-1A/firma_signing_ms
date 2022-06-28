<?php
header('Content-Type: application/json; charset=utf-8');
/**
 * https://phpseclib.com/docs/rsa
 */
require __DIR__ . '/../vendor/autoload.php';
require './../src/sign.php';

// Config
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Extraer datos del body request
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$error['fields'] = [];
if (!isset($input['user_id'])) array_push($error['fields'],'user_id');
if (!isset($input['data'])) array_push($error['fields'],'data');
if (!isset($input['user_id']) || !isset($input['data'])) {
    http_response_code(400);
    $error['messsage'] = 'Missing fields';
    echo json_encode($error);    
    die();
}

$user_id = (isset($input['user_id'])) ? $input['user_id'] : null;
$data = (isset($input['data'])) ? $input['data'] : null;
$obj = sign($user_id,$data );

// Salida del body response en JSON
$myJSON = json_encode($obj);
echo $myJSON;
