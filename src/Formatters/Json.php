<?php

namespace App\Formatters\Json;

function formatJson($diff): false|string
{
    return json_encode($diff);
}
