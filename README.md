# Intrucciones
## Petición
Partes de la petición HTTP para firmar
1. EL Token JWT en el encabezado con el  `user_id: 123`. https://dinochiesa.github.io/jwt/
1. El dato a firmar en el cuerpo

```bash
curl -X POST \
  'http://localhost:8095' \
  --data-raw '{ "data": "hola mundo!" }'
```
## Respuesta esperada
Es el dato o mensaje a firmar en base64 y la firma RSA 
```JSON
{
  "data": "aG9sYSBtdW5kbyE=",
  "signature": "IN5ackUMNi7nz/210U7MWRQLQcyP9QoJbOMI/kcFbALhJQcDnfRGVAt/8lxkMjt4Z8Px7deZUBLpmBpRdIirNMsd6GFq7+3zGMV/cbb7jySHJ73uezlRjBDcnhjUXdm95GKsWFm2d6AhQJqJMkmwUx+JWpvbfl8M6XlyZcS7nRc=",
  "user_id": 123
}
```