<?php

declare(strict_types=1);

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

$giverAbiJson = <<<JSON
{
  "ABI version": 2,
  "header": [
    "time"
  ],
  "functions": [
    {
      "name": "constructor",
      "inputs": [
      ],
      "outputs": [
      ]
    },
    {
      "name": "grant",
      "inputs": [
        {
          "name": "dest",
          "type": "address"
        }
      ],
      "outputs": [
      ]
    }
  ],
  "data": [
  ],
  "events": [
  ]
}
JSON;

// Ton Client instantiation
$tonClient = TonClient::createDefault();

// Get Processing module
$processing = $tonClient->getProcessing();

// Giver address
$giverAddress = '0:653b9a6452c7a982c6dc92b2da9eba832ade1c467699ebb3b43dca6d77b780dd';

// Destination address (change the address to yours)
$destinationAddress = '0:c479ba10644a6715f34f3486a9c6c343c2efa44eeffa4e7d9f4515def7864d21';

// Create abi from JSON
$abi = AbiType::fromJson($giverAbiJson);

$callSet = (new CallSet('grant'))
    ->withInput(
        [
            'dest' => $destinationAddress
        ]
    );

$signer = Signer::fromNone();

// Send rubies
$result = $processing->processMessage(
    $abi,
    $signer,
    null,
    $callSet,
    $giverAddress
);

var_dump($result->getResponseData());

echo 'Total account fees: ' . $result->getFees()->getTotalAccountFees() . PHP_EOL;

echo 'Finished.' . PHP_EOL;