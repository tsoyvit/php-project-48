install: # установить зависимости
	composer install
	npm install
validate: # Запуск composer validate
	composer validate
lint: # Запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 src bin
test:
	composer exec --verbose phpunit tests
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text