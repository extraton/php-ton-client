<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

// Ton Client instantiation
$tonClient = TonClient::createDefault();

// Getting module crypto
$crypto = $tonClient->getCrypto();

// Generate random ed25519 key pair.
$result = $crypto->generateRandomSignKeys();
$keyPair = $result->getKeyPair();

echo 'Public key: ' . $keyPair->getPublic() . PHP_EOL;
echo 'Private key: ' . $keyPair->getSecret() . PHP_EOL;
