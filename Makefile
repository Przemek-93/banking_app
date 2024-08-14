build:
	docker compose build --no-cache

up:
	docker compose up -d

stop:
	docker compose stop

bash:
	docker compose exec php bash
