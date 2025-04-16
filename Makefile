.PHONY: build clean up down reup start stop restart composer npm laravel nginx dev

DOCKER_COMPOSE := $(if $(shell command -v docker-compose), docker-compose, docker compose)

build: composer npm up

clean:
	@$(DOCKER_COMPOSE) down --rmi all
	@docker system prune --all -f

up:
	@$(DOCKER_COMPOSE) up -d 

down:
	@$(DOCKER_COMPOSE) down

reup: down up

start:
	@docker start laravel nginx vite

stop:
	@docker stop $$(docker ps -q)

restart: stop start

composer:
	@$(DOCKER_COMPOSE) run --rm composer

npm:
	@$(DOCKER_COMPOSE) run --rm vite bash -c "npm install && npm audit fix && npm run build"

laravel:
	@$(DOCKER_COMPOSE) exec laravel sh

nginx:
	@$(DOCKER_COMPOSE) exec nginx sh

dev:
	@$(DOCKER_COMPOSE) exec vite bash
