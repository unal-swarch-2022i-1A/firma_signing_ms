# Intrucciones

:warning: **Atención:** este micro-servicio esta implementado como contenedor de docker.

## Petición
Partes de la petición HTTP para firmar
1. EL Token JWT en el encabezado con el  `user_id: 123`. https://dinochiesa.github.io/jwt/
1. El dato a firmar en el cuerpo

```bash
curl --silent --location --request POST 'http://localhost:8092/sign' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data-raw '{ "data": "hola mundo!", "user_id": "1" }'
```

## Respuesta esperada
Es el dato o mensaje a firmar en base64 y la firma RSA 
```JSON
{
  "user_id": "1",
  "data": "hola mundo!",
  "signature": "oJoDodeXFtolL9LcWo2Geh2NA2u+dtjaA0JT45tCGFi4Nmg3zHiuLR1+nnzt6KF4Gekg5QlXZmh2LsrXxXmplB/Py0/+k659JnmTQbnD8bLFxrJo/sYOytopo66Xltb2Oq28WoUS94pdBWOneY2WzU1nyeY1ahYi6vPzNkO47yE="
}
```
## Cliente RPC del mciro-servicio Keys
```bash
cd ../src
php -r 'include "KeysRPCClient.php"; KeysRPCClient::run(public,1);'
```

## Testing
Desde la shell del contenedor Docker
```bash
cd /var/www/html
./../vendor/bin/phpunit ./../tests
``` 