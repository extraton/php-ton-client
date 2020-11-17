<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

$tonClient = TonClient::createDefault();

$result = $tonClient->version();

echo 'TON SDK version: ' . $result->getVersion() . PHP_EOL;
