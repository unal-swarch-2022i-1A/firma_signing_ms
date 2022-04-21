<?php
header('Content-Type: application/json; charset=utf-8');
/**
 * https://phpseclib.com/docs/rsa
 */
require __DIR__ . '/../vendor/autoload.php';

use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Extraer el usuaroi del JWT
// https://dinochiesa.github.io/jwt/
$token = null;
$headers = apache_request_headers();
if(isset($headers['authorization'])){
  $matches = array();
  preg_match('/Bearer\s(\S+)/', $headers['authorization'], $matches);
  if(isset($matches[1])){
    $token = $matches[1];
  }
} 
$obj['token'] = $token;
$tks = explode('.', $token);
list($headb64, $bodyb64, $cryptob64) = $tks;
$payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
$obj['user_id'] = $payload->user_id;

// La llave que llega firma_keys_ms
$key = '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDAZSfzwxio51ITASN7m7Ck5GMA8gUpSPHKOn6lYQ17ZqGO6YWq
jOzYNL/ipxVWq8Ztr1zueOpIFSnKxf7XG1u/FIdkUlufpRcUSahrXxbUJX36+Qqk
ujRvC1XwZTRmhoqiLaO8wHxFMXqJrhgAvr5+Yjtpftph731rgN77XLAgCQIDAQAB
AoGAPNbAD4E+JwsfFQtjIQ9WiI4AEKh3oVqDuyNMMRfDn6YQqJSHxCrUKnpjw1R6
lvGyybSOeoqZ6zlmAc0ijPsFw5XVET1U1PR52RgPTBJJB+pYkGW5LJCtT/lkARE/
NoqqkAgRhWBxl5mSyQWHfjsDtcoebdYpMmQbn0NkKHWzaRECQQDzGpIAZZO093GY
D3JaxXdKGnvvRKXn6+cY/FXErtRRVzfIiGUD4fGSP013wdWpgCdHv2ZR5ARv7udo
E91PDiwnAkEAypnyD99jVJdwRUh65k/BHseefCQlJEhslr3g7INNAj5/9IdWhs6B
I98NEeoCiOQ0PvKAU8Mebu38hj8jEfuATwJAaGIERr9WyOFmmRAo3ejj66GrjXVA
d3DHbecLPMSEzdhRT32hQiWGAHHF5aIJCBrKwvfgC1GIxjcijYHaCNPhCQJAGSOp
CZcqeCCiabZoqZNT30HdxIGnqizibIH7Gt3f/FtM/Uad0fRlydGviX2D+wB2CymE
CuC3MgSNxQqoi16tuQJABfEHYSJ5PAt4BbPZ1jXEbjWtN4bHpcuVQE/Sa0AbzRdP
77nK0sDR02StrgmEozg/Rr3bxfaMYAgaQBvz/zgrtg==
-----END RSA PRIVATE KEY-----';
// La carga de la llave
$private = PublicKeyLoader::load($key);
$public = $private->getPublicKey();


// Extraer datos del body request
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
$data = $input['data'];
$data64 = base64_encode($data);
$obj['data'] = $data64;

// Firma
$signature = $private->sign($data);
/* // Alterar el dato
$data .= 'asd'; */

$signature64 = base64_encode($signature);
$obj['signature'] = $signature64;

/* // Análogo de firma_verification_ms:
$obj['verification'] = $public->verify($data, base64_decode($obj['signature'])) ?
'valid signature' :
'invalid signature'; */

// Salida del body response en JSON
$myJSON = json_encode($obj);
echo $myJSON;
