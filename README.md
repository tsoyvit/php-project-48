### Hexlet tests and linter status:
[![Actions Status](https://github.com/tsoyvit/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/tsoyvit/php-project-48/actions)
[![Check](https://github.com/tsoyvit/php-project-48/actions/workflows/check.yml/badge.svg)](https://github.com/tsoyvit/php-project-48/actions/workflows/check.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/00ddf00092642a675397/maintainability)](https://codeclimate.com/github/tsoyvit/php-project-48/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/00ddf00092642a675397/test_coverage)](https://codeclimate.com/github/tsoyvit/php-project-48/test_coverage)
## Демонстрация gendiff
[![asciicast](https://asciinema.org/a/mGmCRaGNJpp2RiP7XH5ycFQqF.svg)](https://asciinema.org/a/mGmCRaGNJpp2RiP7XH5ycFQqF)

## Пример использования

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