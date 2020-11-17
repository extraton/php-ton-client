<?php

declare(strict_types=1);

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

// Ton Client instantiation
$tonClient = TonClient::createDefault();

// Get Net module
$net = $tonClient->getNet();

// Build query
$query = (new ParamsOfSubscribeCollection('transactions'))
    ->addResultField('id', 'block_id', 'balance_delta')
    ->addFilter('balance_delta', Filters::GT, '0x5f5e100');

// Get result with handle and start watching
$result = $net->subscribeCollection($query);

echo "Handle: {$result->getHandle()}." . PHP_EOL;

$counter = 0;

// Iterate generator
foreach ($result->getIterator() as $event) {
    $counter++;

    echo "Event counter: {$counter}, event data:" . PHP_EOL;
    var_dump($event->getResult());

    if ($counter > 25) {
        echo 'Manual stop watching.' . PHP_EOL;
        $result->stop(); // or call: $net->unsubscribe($result->getHandle());
    }
}

echo 'Finished.' . PHP_EOL;
