# Projeto Laravel com Docker

Este projeto utiliza Laravel com Docker para facilitar o desenvolvimento local.

## Como rodar o projeto

```bash

# Constrói e sobe os containers definidos no docker-compose.yml
docker-compose up -d --build

# Acesse o projeto Laravel no navegador
http://localhost:8000

# Para e remove os containers
docker-compose down

# Mostra os logs em tempo real
docker-compose logs -f

# Acessa o terminal do container Docker
docker exec -it greenn_project bash