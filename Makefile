build:
	docker compose build --no-cache

up:
	docker compose up -d

stop:
	docker compose stop

test:
	docker compose exec php vendor/bin/phpunit

bash:
	docker compose exec php bash
