.PHONY: help console
bin_dir=vendor/bin

docker-compose.yml:
	cp docker-compose.yml.dist $@

docker/docker-compose.env:
	cp docker/docker-compose.env.dist $@
	sed --in-place "s/{your_unix_local_username}/$(shell whoami)/" $@
	sed --in-place "s/{your_unix_local_uid}/$(shell id -u)/" $@

docker/conf/nginx_vhost.conf:
	cp docker/conf/nginx_vhost.conf.dist $@

start: docker-compose.yml docker/docker-compose.env docker/conf/nginx_vhost.conf ## Launch containers
	docker-compose up -d

stop: ## Stop containers
	docker-compose stop

console: ## Connect to console container
	docker exec -it prisme_console /bin/login -p -f $(shell whoami)

vendor/autoload.php: ## Install composer dependencies
	composer install

cs-check: vendor/autoload.php ## Check PHP CS
	${bin_dir}/php-cs-fixer --version
	${bin_dir}/php-cs-fixer fix -v --diff --dry-run

cs-fix: vendor/autoload.php ## Fix PHP CS
	${bin_dir}/php-cs-fixer --version
	${bin_dir}/php-cs-fixer fix -v --diff

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help
