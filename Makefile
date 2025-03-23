install: # установить зависимости
	composer install
	npm install
validate: # Запуск composer validate
	composer validate
lint: # Запуск phpcs
	composer exec --verbose phpcs -- --standard=PSR12 src bin

