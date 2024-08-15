# Banking app

![image](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![image](https://img.shields.io/badge/Docker-2CA5E0?style=for-the-badge&logo=docker&logoColor=white)

This project is a task designed to showcase a micro banking application implemented with Domain-Driven Design (DDD) principles in a framework-agnostic approach.

## Start

1. Clone repository from `git@github.com:Przemek-93/banking_app.git`
2. Install Makefile - https://makefiletutorial.com/
3. Install Docker - https://docs.docker.com/
4. In the console get into the main project directory and type `make`
5. Wait until the installation process is done
6. After the first installation use `make up` and `make stop` to turn on and turn off the project

## Useful commands

- `make build` - build project, shortcut for `docker compose build --no-cache`
- `make up` - turns on the project, shortcut for `docker compose up -d`
- `make stop` - stops all containers, shortcut for `docker compose stop`
- `make bash` - cli for php container, shortcut for `docker compose exec php bash`
- `make test` - runs all unit tests via phpunit for the application, shortcut for `docker compose exec php vendor/bin/phpunit`
