# A Self-Documenting Makefile: http://marmelab.com/blog/2016/02/29/auto-documented-makefile.html

DOCKER_IMAGE = nofw/base

.PHONY: setup install clean test serve docker help
.DEFAULT_GOAL := help

setup: install .env docker-compose.override.yml ## Setup the project for development

install: ## Install dependencies
	@composer ${COMPOSER_FLAGS} install

.env:
	cp .env.dist .env

docker-compose.override.yml:
	cp docker-compose.override.yml.dist docker-compose.override.yml

clean: ## Clean the working area
	docker-compose down
	rm -rf build/ vendor/ var/cache/* var/docker docker-compose.override.yml

test: ## Run tests
	@vendor/bin/phpunit

serve: ## Start the built-in PHP server
	php -S 0.0.0.0:8080 -t web/ web/app.php

docker: ## Execute commands inside a Docker container
	docker run --rm -it -v $$PWD:/app -w /app ${DOCKER_OPTS} $(DOCKER_IMAGE) make $(filter-out docker, $(MAKECMDGOALS))
	@printf "\033[36mExiting with non-zero status code to abort make. If you see this message your command successfully ran.\033[0m\n"
	exit 1

help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

-include custom.mk
