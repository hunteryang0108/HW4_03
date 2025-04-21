.PHONY: build env clean up down reup start stop restart composer npm laravel nginx dev prod seed

DOCKER_COMPOSE := $(if $(shell command -v docker-compose), docker-compose, docker compose)
HOSTNAME := $(shell hostname -I | awk '{split($$1, ip, "."); printf "sd%02d.yeahlowflicker.directory", (ip[4] - 228) / 2}')

build: composer env npm up

env:
	@$(DOCKER_COMPOSE) run --rm composer sh -c "rm -f .env \
		&& composer run post-root-package-install \
		&& composer run post-create-project-cmd \
		&& mv .env .env.local && mv database/database.sqlite database/local.sqlite \
		&& sed -i '7s/en/zh-tw/;24iDB_DATABASE=./database/local.sqlite' .env.local \
		&& composer run post-root-package-install \
		&& composer run post-create-project-cmd \
		&& mv .env .env.production && mv database/database.sqlite database/production.sqlite \
		&& sed -i '2s/local/production/;4s/true/false/;5s/localhost/$(HOSTNAME)/;7s/en/zh-tw/;24iDB_DATABASE=./database/production.sqlite' .env.production \
		&& ln -sf /.env .env && ln -sf /server.hmr public/hot \
		&& chmod -R a+w ./bootstrap/cache ./storage ./database"

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

prod:
	@sed -i '4s/localhost/$(HOSTNAME)/;' nginx.conf
	@sed -i '/nginx:/,/vite:/{/- \"443:443\"/s/^\(\s*\)#/\1/}' docker-compose.yml
	@sed -i '/vite:/,/network:/{/deploy:/s/^\(\s*\)#/\1/;/replicas:/s/^\(\s*\)#/\1/}' docker-compose.yml
	@$(DOCKER_COMPOSE) run --rm vite bash -c "npm run build"
	@$(DOCKER_COMPOSE) down vite nginx
	@$(DOCKER_COMPOSE) run --rm --service-ports nginx sh -c "apk add --no-cache certbot-nginx \
		&& certbot --nginx --agree-tos --register-unsafely-without-email --non-interactive -d $(HOSTNAME)"
	@$(DOCKER_COMPOSE) up -d nginx
	@docker system prune --all -f

seed:
	@$(DOCKER_COMPOSE) run --rm laravel sh -c "php artisan db:seed --force"
