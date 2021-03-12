help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  build                          (optional) to build docker image locally."
	@echo "  push                           (optional) push docker image to docker hub."
	@echo "  install                        to install composer dependencies."
	@echo "  update                         to update composer dependencies."
	@echo "  test                           to perform all tests."
	@echo "  test-unit                      to perform unit tests."
	@echo "  test-integration               to perform integration tests."
	@echo "  analyze                        to run phpstan on the codebase."
	@echo "  codestyle                      to run php-cs-fixer on the codebase."
	@echo "  codestyle-fix                  to run php-cs-fixer on the codebase with code formatting."

build:
	docker build --tag maxvx/php-ton-client:latest . 2>&1

push:
	docker image push maxvx/php-ton-client:latest 2>&1

install:
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest composer install 2>&1

update:
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest composer update 2>&1

test:
	if [ ! -f "./vendor/bin/phpunit" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest ./vendor/bin/phpunit --testdox tests/$(FOLDER) 2>&1

test-unit:
	$(MAKE) test FOLDER="Unit"

test-integration:
	$(MAKE) test FOLDER="Integration"

analyze:
	if [ ! -f "./vendor/bin/phpstan" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest ./vendor/bin/phpstan analyze src 2>&1

codestyle:
	if [ ! -f "./vendor/bin/php-cs-fixer" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest ./vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff-format=udiff --dry-run --allow-risky=yes 2>&1

codestyle-fix:
	if [ ! -f "./vendor/bin/php-cs-fixer" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app maxvx/php-ton-client:latest ./vendor/bin/php-cs-fixer fix 2>&1