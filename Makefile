install: # установить зависимости
	composer install
validate: # Запуск composer validate
	composer validate
lint: # Запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 src bin
lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests
test:
	composer exec --verbose phpunit tests
test-coverage:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-clover build/logs/clover.xml
test-coverage-text:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text
