<?php

declare(strict_types=1);

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

$tonClient = new TonClient(
    [
        'network' => [
            'server_address' => 'net.ton.dev'
        ]
    ]
);

$net = $tonClient->getNet();

// Build query
$query = new ParamsOfSubscribeCollection('transactions');
$query->addResultField('id', 'block_id', 'balance_delta');
$query->addFilter('balance_delta', Filters::GT, '0x5f5e100');

// Get result with handle and start watching
$result = $net->subscribeCollection($query);

echo "Handle: {$result->getHandle()}." . PHP_EOL;

$counter = 0;

// Iterate generator
foreach ($result as $event) {
    $counter++;

    echo "Event counter: {$counter}, event data:" . PHP_EOL;
    var_dump($event->getResult());

    if ($counter > 25) {
        echo 'Manual stop watching.' . PHP_EOL;
        $result->stop();
    }
}

echo 'Finished.' . PHP_EOL;
