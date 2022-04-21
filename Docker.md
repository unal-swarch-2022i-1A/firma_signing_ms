# Docker
```bash
docker run --rm --interactive --tty --volume "%cd%:/app" --workdir /app composer install
docker-compose --project-name "firma" build
docker-compose --project-name "firma" up --detach
docker exec -it firma_signing_ms /bin/bash
```