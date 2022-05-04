# Intrucciones
## Petición
Partes de la petición HTTP para firmar
1. EL Token JWT en el encabezado con el  `user_id: 123`. https://dinochiesa.github.io/jwt/
1. El dato a firmar en el cuerpo

```bash
curl -s -X POST \
  'http://localhost:8095' \
  --header 'Authorization: Bearer eyJhbGciOiJSUzI1NiJ9.eyJ1c2VyX2lkIjoyMzR9.tcmQ59lzcT5xCuydpU9A2rrFAcZpk929LPxXYKIzs30O0Bb9PvFZedGDrkwSGpo9mph6OszFGS7Bl4XTQ05JkDzkFVkrUfD8hSRGpBNuWqGmIubd2j4CGaBZoqg05ne_nyzzdpU-lFLw8BWfwKSWUhg5P9I_KBC3XQioNKSq7IyL8oNV5vhiwLKpX0qBCkE_BrdQ0PGirGPpFLBzOizrPln4ZanmUwrEyA64-XXUMBIyoyaCKZ3WRtFRJCjGCqbcXo6NxEIihG22l0H0H4-cm_t6FsPea7KzivKg5us-xxsbF_anQzjN36sSU65zE6IFxtuN1Y7ePv7PgvM7Ix2e8w' \
  --header 'Content-Type: application/json' \
  --data-raw '{
    "data": "hola mundo!"
}'
```
## Respuesta esperada
Es el dato o mensaje a firmar en base64 y la firma RSA 
```JSON
{
  "data": "aG9sYSBtdW5kbyE=",
  "signature": "IN5ackUMNi7nz/210U7MWRQLQcyP9QoJbOMI/kcFbALhJQcDnfRGVAt/8lxkMjt4Z8Px7deZUBLpmBpRdIirNMsd6GFq7+3zGMV/cbb7jySHJ73uezlRjBDcnhjUXdm95GKsWFm2d6AhQJqJMkmwUx+JWpvbfl8M6XlyZcS7nRc=",
  "user_id": 234
}
```