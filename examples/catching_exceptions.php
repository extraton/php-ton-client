<?php

declare(strict_types=1);

use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

try {
    // Ton Client instantiation
    $tonClient = new TonClient(
        [
            'network' => [
                'server_address' => 'unknown',
            ]
        ]
    );

    // Get New module
    $net = $tonClient->getNet();

    // Prepare query
    $query = (new ParamsOfSubscribeCollection('transactions'))
        ->addResultField('id')
        ->addOrderBy('id', OrderBy::DESC);

    // Method call using the network
    $net->subscribeCollection($query);
} catch (TonException $exception) {
    echo 'Exception code: ' . $exception->getCode() . PHP_EOL;
    echo 'Exception message: ' . $exception->getMessage() . PHP_EOL;
}
