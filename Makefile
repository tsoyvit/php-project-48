install: # установить зависимости
	composer install
validate: # Запуск composer validate
	composer validate
lint: # Запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 src bin
test:
	composer exec --verbose phpunit tests
test-coverage:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-clover=build/logs/clover.xml --coverage-filter=src/
test-coverage-html:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html=coverage/ --coverage-filter=src/
test-coverage-text:
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text --coverage-filter=src/ --no-logging