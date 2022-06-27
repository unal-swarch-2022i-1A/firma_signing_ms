# Docker

## Build
```bash
docker build -t 'firma_signing_ms' .
```

## Development
```bash
docker rm -f 'firma_signing_ms_dev' && \
docker run -it -d \
    --name 'firma_signing_ms_dev' \
    -v $(pwd)/public:/var/www/html/ \
    -v $(pwd)/src:/var/www/src/ \
    -v $(pwd)/tests:/var/www/tests/ \
    -p 3002:80 \
    --add-host=host.docker.internal:host-gateway \
    'firma_signing_ms' && \
docker logs --tail 1000 -f 'firma_signing_ms_dev'  
docker exec -it 'firma_signing_ms_dev' /bin/bash
```

## Production
```bash
docker rm -f 'firma_signing_ms' && \
docker run -it -d \
    --name 'firma_signing_ms' \
    -p 8092:80 \
    --add-host=host.docker.internal:host-gateway \
    'firma_signing_ms' && \
docker logs --tail 1000 -f 'firma_signing_ms'  
docker exec -it 'firma_signing_ms' /bin/bash
```