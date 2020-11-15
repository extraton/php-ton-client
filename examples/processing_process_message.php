<?php

declare(strict_types=1);

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../vendor/autoload.php';

$tonClient = new TonClient(
    [
        'network' => [
            'server_address' => 'net.ton.dev'
        ]
    ]
);

$dataProvider = new DataProvider($tonClient);
$processing = $tonClient->getProcessing();
$abi = $tonClient->getAbi();
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

$resultOfProcessMessage = $processing->processMessage(
    $abiParams,
    $signer,
    $deploySet,
    $callSet,
    null,
    null,
    true
);

foreach ($resultOfProcessMessage as $event) {
    var_dump($event->getType());
}

var_dump($resultOfProcessMessage->getFees()->getTotalAccountFees());
