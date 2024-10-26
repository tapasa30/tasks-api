DOCKER_COMPOSE := docker-compose -p tasks_api -f docker-compose.yml

build:
	@$(DOCKER_COMPOSE) build --no-cache

up:
	@$(DOCKER_COMPOSE) up -d

down:
	@$(DOCKER_COMPOSE) down

php_bash:
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php bash

create_db:
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console doctrine:database:create

deploy:
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php composer install
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console doctrine:migrations:migrate --no-interaction
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console doctrine:fixtures:load --append
	@$(DOCKER_COMPOSE) exec --user=$(shell id -u) php php bin/console cache:clear
