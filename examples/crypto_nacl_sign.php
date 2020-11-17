<?php

declare(strict_types=1);

use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

// Ton Client instantiation
$tonClient = TonClient::createDefault();

// Getting module crypto
$crypto = $tonClient->getCrypto();

// Unsigned test message
$unsigned = base64_encode('Test Message');
// Signing secret key
$secretKey = '56b6a77093d6fdf14e593f36275d872d75de5b341942376b2a08759f3cbae78f1869b7ef29d58026217e9cf163cbfbd0de889bdf1bf4daebf5433a312f5b8d6e';

// Sign data using the signer's secret key.
$result = $crypto->naclSign($unsigned, $secretKey);

// Showing result
echo 'Unsigned: ' . $unsigned . PHP_EOL;
echo 'Secret key: ' . $secretKey . PHP_EOL;
echo 'Signed: ' . $result->getSigned() . PHP_EOL;
