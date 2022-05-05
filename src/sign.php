<?php
/**
 * https://phpseclib.com/docs/rsa
 */
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\PublicKeyLoader;
function getKey($user) {
    // La llave que llega [firma_keys_ms]
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

    return $key;
}
function sign($user,$data) {
    $obj['user_id'] = $user;
    $obj['data'] = $data;

    $key = getKey($user);

    // La carga de la llave
    $private = PublicKeyLoader::load($key);
    $public = $private->getPublicKey();

    // Firma
    $signature = $private->sign($data);
    /* // Alterar el dato
    $data .= 'asd'; */

    $signature64 = base64_encode($signature);
    $obj['signature'] = $signature64;  

    return $obj;
}
?>