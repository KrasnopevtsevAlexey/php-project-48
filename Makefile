install:
	composer install

gendiff:
	./bin/gendiff

lint:
	composer exec --verbose phpcs -- --standard=PSR12 bin src

validate:
	composer validate

.PHONY: install gendiff lint validate
