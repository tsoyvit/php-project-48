<?php

namespace Differ\Formatters\Json;

function formatJson(array $diff): false|string
{
    return json_encode($diff, JSON_UNESCAPED_UNICODE);
}
