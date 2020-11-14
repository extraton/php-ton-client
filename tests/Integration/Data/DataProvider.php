<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient\Data;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\ParamsOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\TonClient;

use function base64_encode;
use function file_get_contents;
use function filesize;
use function fopen;
use function fread;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class DataProvider
{
    private TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function getEventsTvc(): string
    {
        $path = __DIR__ . '/Events.tvc';
        $bin = fread(fopen($path, 'rb'), filesize($path));

        return base64_encode($bin);
    }

    public function getSubscriptionTvc(): string
    {
        $path = __DIR__ . '/Subscription.tvc';
        $bin = fread(fopen($path, 'rb'), filesize($path));

        return base64_encode($bin);
    }

    public function getEventsAbiJson(): string
    {
        return file_get_contents(__DIR__ . '/Events.abi.json');
    }

    public function getSubscriptionAbiJson(): string
    {
        return file_get_contents(__DIR__ . '/Subscription.abi.json');
    }

    public function getGiverAbiJson(): string
    {
        return file_get_contents(__DIR__ . '/Giver.abi.json');
    }

    public function getEventsAbiArray(): array
    {
        return (array)json_decode($this->getEventsAbiJson(), true, 32, JSON_THROW_ON_ERROR);
    }

    public function getSubscriptionAbiArray(): array
    {
        return (array)json_decode($this->getSubscriptionAbiJson(), true, 32, JSON_THROW_ON_ERROR);
    }

    public function getGiverAbiArray(): array
    {
        return (array)json_decode($this->getGiverAbiJson(), true, 32, JSON_THROW_ON_ERROR);
    }

    public function getKeyPairJson(): string
    {
        return file_get_contents(__DIR__ . '/KeyPair.json');
    }

    public function getKeyPairArray(): array
    {
        return (array)json_decode($this->getKeyPairJson(), true, 32, JSON_THROW_ON_ERROR);
    }

    public function getEventsTime(): int
    {
        return 1599458364291;
    }

    public function getEventsExpire(): int
    {
        return 1599458404;
    }

    public function getPublicKey(): string
    {
        return '4c7c408ff1ddebb8d6405ee979c716a14fdd6cc08124107a61d3c25597099499';
    }

    public function getPrivateKey(): string
    {
        return 'cc8929d635719612a9478b9cd17675a39cfad52d8959e8a177389b8c0b9122a7';
    }

    /**
     * @return string
     */
    public function getGiverAddress(): string
    {
        return '0:653b9a6452c7a982c6dc92b2da9eba832ade1c467699ebb3b43dca6d77b780dd';
    }

    /**
     * @return string
     */
    public function getWalletAddress(): string
    {
        return '0:2222222222222222222222222222222222222222222222222222222222222222';
    }

    /**
     * @param string $address
     * @throws \JsonException
     */
    public function sendTons(string $address): void
    {
        $abi = AbiParams::fromArray($this->getGiverAbiArray());
        $callSet = new CallSetParams('grant', null, ['addr' => $address]);
        $giverAddress = $this->getGiverAddress();
        $signer = SignerParams::fromNone();

        $this->tonClient->getProcessing()->processMessage(
            $abi,
            $signer,
            null,
            $callSet,
            $giverAddress
        );
    }
}
