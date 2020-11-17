<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../../vendor/autoload.php';

// Ton Client instantiation
$tonClient = new TonClient(
    [
        'network' => [
            'server_address' => 'net.ton.dev'
        ]
    ]
);

// Getting module crypto
$crypto = $tonClient->getCrypto();

// Generate random ed25519 key pair.
$result = $crypto->generateRandomSignKeys();

// Showing result
var_dump($result->getKeyPair());
