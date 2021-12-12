.PHONY: h
.DEFAULT_GOAL = h

COMPOSER = composer
SYMFONY_CONSOLE = php bin/console
PHPUNIT = php bin/phpunit

## —— Database 🗄️———————————————————————————————————————————————————————————————
db: ## [DB] Reset database
	$(SYMFONY_CONSOLE) doctrine:database:drop --if-exists --force
	$(SYMFONY_CONSOLE) doctrine:database:create --if-not-exists
	$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration
	$(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction

dbt: ## [DB] SQLite - Reset database test
	$(SYMFONY_CONSOLE) doctrine:database:drop --env=test --force
	$(SYMFONY_CONSOLE) doctrine:database:create --env=test
	$(SYMFONY_CONSOLE) doctrine:schema:create --env=test

## —— PHPUnit 🔎️ ———————————————————————————————————————————————————————————————
tu: ## [TEST] test-unit - Lancement des tests unitaire
	$(PHPUNIT) tests/Unit/

tf: ## [TEST] test-func - Lancement des tests fonctionnel
	$(PHPUNIT) tests/Func/

t: tf tu	## Lancement de tous tests

## —— Blackfire 🔥️ ———————————————————————————————————————————————————————————————
b: ## [PERF] blackfire - Lancement de blackfire
	-blackfire agent:start
	symfony serve

## —— Others 🛠️️ ———————————————————————————————————————————————————————————————
h: ## help - Liste des commandes
	@egrep '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
