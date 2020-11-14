<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\TonClient\Abi;
use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Boc;
use Extraton\TonClient\Crypto;
use Extraton\TonClient\Net;
use Extraton\TonClient\Processing;
use Extraton\TonClient\TonClient;
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

    protected DataProvider $dataProvider;

    public function setUp(): void
    {
        $this->tonClient = $this->getTonClient();
        $this->processing = $this->tonClient->getProcessing();
        $this->crypto = $this->tonClient->getCrypto();
        $this->abi = $this->tonClient->getAbi();
        $this->boc = $this->tonClient->getBoc();
        $this->net = $this->tonClient->getNet();
        $this->utils = $this->tonClient->getUtils();
        $this->dataProvider = new DataProvider($this->tonClient);
    }

    protected function getTonClient(): TonClient
    {
        return new TonClient(
            [
                'network' => [
                    'server_address'             => 'net.ton.dev',
                    'message_retries_count'      => 5,
                    'message_processing_timeout' => 40000,
                    'wait_for_timeout'           => 40000,
                    'out_of_sync_threshold'      => 15000,
                    'access_key'                 => ''
                ],
                'crypto'  => [
                    'fish_param' => ''
                ],
                'abi'     => [
                    'message_expiration_timeout'             => 40000,
                    'message_expiration_timeout_grow_factor' => 1.5
                ]
            ],
            Binding::createDefault()
        );
    }
}
