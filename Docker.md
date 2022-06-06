# Docker
```bash
docker-compose --project-name "firma" down
docker-compose --project-name "firma" build
docker-compose --project-name "firma" up --detach
docker-compose --project-name "firma" logs -f --tail 1000 
docker exec -it firma_signing_ms /bin/bash
```