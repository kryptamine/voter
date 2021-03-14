## Description
Simple poll application with multiple-voting protection and reactive updates.

## Components
- php-fpm 
- nginx
- echo (socket server)
- postgresql (database)
- redis (cache + websocket message bus)

## Requirements
- **[Docker](https://www.docker.com/)**
- MacOS / Linux
- Ports: [80, 6001, 6309]

## Build & Run
1. Run ./run.sh
2. Open http://localhost
