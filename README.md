### Hexlet tests and linter status:
[![Actions Status](https://github.com/tsoyvit/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/tsoyvit/php-project-48/actions)
[![Check](https://github.com/tsoyvit/php-project-48/actions/workflows/check.yml/badge.svg)](https://github.com/tsoyvit/php-project-48/actions/workflows/check.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/511187a85ae447a19d9c/maintainability)](https://codeclimate.com/github/tsoyvit/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/511187a85ae447a19d9c/test_coverage)](https://codeclimate.com/github/tsoyvit/php-project-48/test_coverage)

Вычислитель отличий – программа, определяющая разницу между двумя структурами данных.

Поддержка разных входных форматов: yaml и json. 
Генерация отчета в виде plain text, stylish и json

## Минимальные требования

- PHP 8.2 или выше
- Composer (для управления зависимостями)
- Git (для установки проекта)

## Установка

1. Установите репозиторий:

   ```bash
   git clone https://github.com/tsoyvit/php-project-48.git
   cd php-project-48
   ```

2. Установите зависимости:

   ```bash
   make install
   ```

## Пример использования Cli утилиты:

Сравнение плоских файлов json
[![asciicast](https://asciinema.org/a/FJoqOIULlkkrMNTZbcSGwDw2Q.svg)](https://asciinema.org/a/FJoqOIULlkkrMNTZbcSGwDw2Q)

Сравнение плоских файлов yaml
[![asciicast](https://asciinema.org/a/06WdT0MCXgXHn3PdSZCbJrLnU.svg)](https://asciinema.org/a/06WdT0MCXgXHn3PdSZCbJrLnU)

Формат вывода stylish (по умолчанию)
[![asciicast](https://asciinema.org/a/HvXuseXdiGiJCbKqd2TeXry66.svg)](https://asciinema.org/a/HvXuseXdiGiJCbKqd2TeXry66)

Формат вывода plain
[![asciicast](https://asciinema.org/a/SqpfpL6MoUyEsWW79TetsWziR.svg)](https://asciinema.org/a/SqpfpL6MoUyEsWW79TetsWziR)

Формат вывода json
[![asciicast](https://asciinema.org/a/xBYaPH53YNUUrFbunU3oP8GMu.svg)](https://asciinema.org/a/xBYaPH53YNUUrFbunU3oP8GMu)

## Пример использования библиотеки:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use function App\Differ\genDiff;

// Сравнение JSON
$jsonDiff = genDiff('file1.json', 'file2.json');
echo $jsonDiff;

// Сравнение YAML
$yamlDiff = genDiff('file1.yaml', 'file2.yaml');
echo $yamlDiff;

// Сравнение JSON и YAML
$jsonYamlDiff = genDiff('file1.json', 'file2.yaml');
echo $jsonYamlDiff;

//Пример вывода:
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
