MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PWD := $(dir $(MAKEPATH))

TTY_PARAM := $(shell tty > /dev/null && echo "" || echo "-T")
WINPTY := $(shell command -v winpty && echo "winpty " ||  echo "")
NETWORK := $(shell docker network ls | grep workwize-store | sed 's/ \{1,\}/ /g' | cut -d " " -f 2 | cut -d "_" -f 1)

ENV_FILE := ./.env

# Setup Environment Variables
include ${ENV_FILE}
export

ifeq ($(workwize-store_NETWORK),)
	workwize-store_NETWORK=$(NETWORK)
	export workwize-store_NETWORK := $(NETWORK)
endif

export WWWUSER ?= $(shell id -u)
export WWWGROUP ?= $(shell id -g)

setup:
	make stop
	make clean
	make build
	make start
	make app-key
	make db-reset

build:
	make stop
	docker compose --env-file ${ENV_FILE} build --no-cache

start:
	make start-fast
	make composer-install

start-fast:
	make stop
	$(WINPTY)docker compose --env-file ${ENV_FILE} up -d

stop:
	$(WINPTY)docker compose --env-file ${ENV_FILE} down

app-key:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash -c "php artisan key:generate --ansi"

clean:
	$(WINPTY)docker compose --env-file ${ENV_FILE} down -v

db-reset:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-mysql bash -c "/var/lib/db/reset-db.sh"; \
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash -c "php artisan migrate:fresh --seed"

db-migrations:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash -c "php artisan migrate"

db-seeds:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash -c "php artisan db:seed"

db-seed:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash -c "composer dump-autoload && php artisan db:seed --class=$(filter-out $@,$(MAKECMDGOALS))"

sh bash shell:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app bash

restart-nginx:
	$(WINPTY)docker compose --env-file ${ENV_FILE} restart workwize-store-nginx

composer-install:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app sh -c "composer install --no-interaction"

composer-dumpautoload:
	$(WINPTY)docker compose --env-file ${ENV_FILE} exec $(TTY_PARAM) workwize-store-app sh -c "composer dump-autoload"

logs:
	@$(WINPTY)docker compose --env-file ${ENV_FILE} exec workwize-store-app sh -c "touch storage/logs/laravel.log && tail -f -n +1 -s 1 storage/logs/laravel.log"
