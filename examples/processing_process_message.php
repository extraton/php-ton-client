<?php

declare(strict_types=1);

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

// Ton Client instantiation
$tonClient = TonClient::createDefault();

$dataProvider = new DataProvider($tonClient);

// Get Processing module
$processing = $tonClient->getProcessing();

// Get Abi module
$abi = $tonClient->getAbi();

//  Get Crypto module
$crypto = $tonClient->getCrypto();

$abiParams = AbiType::fromArray($dataProvider->getEventsAbiArray());
$deploySet = new DeploySet($dataProvider->getEventsTvc());
$keyPair = $crypto->generateRandomSignKeys()->getKeyPair();
$signer = Signer::fromKeys($keyPair);
$callSet = (new CallSet('constructor'))->withFunctionHeaderParams($keyPair->getPublic());

$resultOfEncodeMessage = $abi->encodeMessage(
    $abiParams,
    $signer,
    $deploySet,
    $callSet
);

$address = $resultOfEncodeMessage->getAddress();

$dataProvider->sendTons($address);

// Creates message, sends it to the network and monitors its processing
$resultOfProcessMessage = $processing->processMessage(
    $abiParams,
    $signer,
    $deploySet,
    $callSet,
    null,
    null,
    true
);

// Iterate generator
foreach ($resultOfProcessMessage->getIterator() as $event) {
    echo 'New event ' . $event->getType() . PHP_EOL;

    var_dump($event->getResponseData());
}

echo 'Fees: ' . PHP_EOL;
var_dump($resultOfProcessMessage->getFees());

echo 'Finished.' . PHP_EOL;
