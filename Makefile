help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  build                          (optional) to build docker image locally."
	@echo "  install                        to install dependencies."
	@echo "  test                           to perform all tests."
	@echo "  test-unit                      to perform unit tests."
	@echo "  test-integration               to perform integration tests."
	@echo "  phpstan                        to run phpstan on the codebase."
	@echo "  codestyle                      to run php-cs-fixer on the codebase."

build:
	docker build --tag extraton/php-ton-client-checkup:0.1 .

install:
	docker run --rm -it -v ${PWD}:/app extraton/php-ton-client-checkup:0.1 composer install

test:
	if [ ! -f "./vendor/bin/phpunit" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app extraton/php-ton-client-checkup:0.1 ./vendor/bin/phpunit --testdox tests/$(FOLDER)

test-unit:
	$(MAKE) test FOLDER="Unit"

test-integration:
	$(MAKE) test FOLDER="Integration"

phpstan:
	if [ ! -f "./vendor/bin/phpstan" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app extraton/php-ton-client-checkup:0.1 ./vendor/bin/phpstan analyze src

codestyle:
	if [ ! -f "./vendor/bin/php-cs-fixer" ]; then $(MAKE) install; fi;
	docker run --rm -it -v ${PWD}:/app extraton/php-ton-client-checkup:0.1 ./vendor/bin/php-cs-fixer fix --config=.php_cs.dist --diff-format=udiff --dry-run --allow-risky=yes
