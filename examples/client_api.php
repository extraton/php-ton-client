<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

$tonClient = TonClient::createDefault();

$result = $tonClient->getApiReference();

echo 'API: ' . print_r($result->getApi(), true) . PHP_EOL;
