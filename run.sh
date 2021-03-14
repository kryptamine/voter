#!/usr/bin/env bash

# Ensure that Docker is running...
if ! docker info > /dev/null 2>&1; then
    echo -e "${WHITE}Docker is not running.${NC}" >&2

    exit 1
fi

function not_running {
    echo -e "${WHITE}Voter is not running.${NC}" >&2
    exit 1
}

PSRESULT="$(docker-compose ps -q)"

if docker-compose ps | grep 'Exit'; then
    echo -e "${WHITE}Shutting down old processes...${NC}" >&2

    docker-compose down > /dev/null 2>&1

    EXEC="no"
elif [ -n "$PSRESULT" ]; then
    EXEC="yes"
else
    EXEC="no"
fi

echo $EXEC

if [ "$EXEC" == "yes" ]; then
    docker-compose up -d && \
    docker-compose exec \
    -u www \
    app \
    composer install && \
    docker-compose exec \
        -u www \
        app \
        php artisan migrate
else
    not_running
fi
