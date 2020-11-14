<?php

declare(strict_types=1);

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\FunctionHeaderParams;
use Extraton\TonClient\Entity\Abi\ParamsOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\TonClient;

require __DIR__ . '/../../vendor/autoload.php';

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

$abiParams = AbiParams::fromArray($dataProvider->getEventsAbiArray());
$deploySet = new DeploySetParams($dataProvider->getEventsTvc());

$keyPair = $crypto->generateRandomSignKeys()->getKeyPair();
$signer = SignerParams::fromKeys($keyPair);

$functionHeaderParams = new FunctionHeaderParams($keyPair->getPublic());

$callSet = new CallSetParams('constructor', $functionHeaderParams);

$resultOfEncodeMessage = $abi->encodeMessage(
    $abiParams,
    $signer,
    $deploySet,
    $callSet
);

$address = $resultOfEncodeMessage->getAddress();

$dataProvider->sendTons($address);

$paramsOfEncodeMessage = new ParamsOfEncodeMessage(
    $abiParams,
    $signer,
    $deploySet,
    $callSet
);

$resultOfProcessMessage = $processing->processMessage(
    $paramsOfEncodeMessage,
    true
);

foreach ($resultOfProcessMessage as $event) {
    var_dump($event->getType());
}

var_dump($resultOfProcessMessage->getFees()->getTotalAccountFees());
