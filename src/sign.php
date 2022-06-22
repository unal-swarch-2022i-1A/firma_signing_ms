<?php
/**
 * https://phpseclib.com/docs/rsa
 */
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;
require dirname(__FILE__).'/KeysRPCClient.php';
use App\KeysRPCClient as KeysRPCClient;

function getKey($user) {

    $keysRPCClient = new KeysRPCClient();
    $key = $keysRPCClient->run("private",$user);
    return $key;
}
function sign($user,$data) {
    $obj['user_id'] = $user;
    $obj['data'] = $data;

    $key = getKey($user);    

    // La carga de la llave
    $private = PublicKeyLoader::load($key);
    //$public = $private->getPublicKey();

    // Firma
    $signature = $private->sign($data);
    /* // Alterar el dato
    $data .= 'asd'; */

    $signature64 = base64_encode($signature);
    $obj['signature'] = $signature64;  

    return $obj;
}
?>