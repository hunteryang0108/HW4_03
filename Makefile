.PHONY: build clean up down reup start stop restart composer npm laravel nginx vite

build: composer npm up

clean:
	@docker compose down --rmi all
	@docker system prune --all -f

up:
	@docker compose up -d 

down:
	@docker compose down

reup: down up

start:
	@docker start laravel nginx vite

stop:
	@docker stop $$(docker ps -q)

restart: stop start

composer:
	@docker compose run --rm composer

npm:
	@docker compose run --rm vite bash -c "npm install && npm audit fix && npm run build"

laravel:
	@docker compose exec laravel sh

nginx:
	@docker compose exec nginx sh

vite:
	@docker compose exec vite bash
