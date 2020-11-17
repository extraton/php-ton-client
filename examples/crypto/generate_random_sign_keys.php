<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../../vendor/autoload.php';

$tonClient = new TonClient(
    [
        'network' => [
            'server_address' => 'net.ton.dev'
        ]
    ]
);

$crypto = $tonClient->getCrypto();

$result = $crypto->generateRandomSignKeys();

var_dump($result->getKeyPair());
