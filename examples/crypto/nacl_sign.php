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

$unsigned = base64_encode('Test Message');
$secretKey = '56b6a77093d6fdf14e593f36275d872d75de5b341942376b2a08759f3cbae78f1869b7ef29d58026217e9cf163cbfbd0de889bdf1bf4daebf5433a312f5b8d6e';

$result = $crypto->naclSign($unsigned, $secretKey);

var_dump([
    'unsigned' => $unsigned,
    'secretKey' => $secretKey,
    'signed' => $result->getSigned(),
]);
