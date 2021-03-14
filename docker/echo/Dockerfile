FROM node:15-alpine

WORKDIR /

RUN apk add --no-cache --virtual .build-deps \
    && apk add --no-cache \
        bash \
        gettext \
    && apk del -f .build-deps

RUN npm install -g laravel-echo-server

COPY ./docker/echo/laravel-echo-server.json /

ENTRYPOINT laravel-echo-server start --force
