<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient\Data;

use function base64_encode;
use function file_get_contents;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class DataProvider
{
    public function getEventsTvc(): string
    {
        $path = __DIR__ . '/Events.tvc';
        $bin = fread(fopen($path, 'rb'), filesize($path));

        return base64_encode($bin);
    }

    public function getEventsAbiJson(): string
    {
        return file_get_contents(__DIR__ . '/Events.abi.json');
    }

    public function getEventsAbiArray(): array
    {
        return (array)json_decode($this->getEventsAbiJson(), true, 32, JSON_THROW_ON_ERROR);
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
}
