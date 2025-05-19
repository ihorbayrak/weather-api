#!/bin/bash

# just to be sure that no traces left
docker-compose down -v

# building and running docker-compose file
docker-compose build && docker-compose up -d

# container ids by service name
app_container=$(docker ps -aqf "name=genesis-app")
db_container=$(docker ps -aqf "name=genesis-db")

# wait until PostgreSQL is ready
echo "Waiting for PostgreSQL to be ready..."
until docker exec -i "${db_container}" pg_isready -U "${POSTGRES_USER}" -d "${POSTGRES_DB}" -h "${POSTGRES_HOST:-localhost}" >/dev/null 2>&1; do
    sleep 1
done
echo "PostgreSQL is ready."

# installing composer dependencies inside the PHP container
docker exec -i "${app_container}" bash -c "composer install"

# generating app key and clearing cache
docker exec -i "${app_container}" bash -c "php bin/console doctrine:database:create --if-not-exists"
docker exec -i "${app_container}" bash -c "php bin/console doctrine:migrations:migrate --no-interaction"
docker exec -i "${app_container}" bash -c "php bin/console cache:clear"
docker exec -i "${app_container}" bash -c "php bin/console cache:warmup"
