install:
	composer install

gendiff:
	./bin/gendiff

lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin src

validate:
	composer validate

.PHONY: install gendiff lint validate

test:
	composer exec --verbose phpunit tests

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml