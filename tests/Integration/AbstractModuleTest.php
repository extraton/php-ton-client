<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\Tests\Integration\TonClient\Data\EventSaver;
use Extraton\TonClient\Abi;
use Extraton\TonClient\Boc;
use Extraton\TonClient\Crypto;
use Extraton\TonClient\Net;
use Extraton\TonClient\Processing;
use Extraton\TonClient\TonClient;
use Extraton\TonClient\Tvm;
use Extraton\TonClient\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Abstract class for setup Ton client
 */
abstract class AbstractModuleTest extends TestCase
{
    protected TonClient $tonClient;

    protected Processing $processing;

    protected Crypto $crypto;

    protected Abi $abi;

    protected Boc $boc;

    protected Net $net;

    protected Utils $utils;

    protected Tvm $tvm;

    protected DataProvider $dataProvider;

    protected EventSaver $eventSaver;

    public function setUp(): void
    {
        $this->tonClient = TonClient::createDefault();
        $this->processing = $this->tonClient->getProcessing();
        $this->crypto = $this->tonClient->getCrypto();
        $this->abi = $this->tonClient->getAbi();
        $this->boc = $this->tonClient->getBoc();
        $this->net = $this->tonClient->getNet();
        $this->utils = $this->tonClient->getUtils();
        $this->tvm = $this->tonClient->getTvm();
        $this->dataProvider = new DataProvider($this->tonClient);
        $this->eventSaver = new EventSaver();
    }
}
