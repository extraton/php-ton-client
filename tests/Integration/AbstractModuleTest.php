<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\TonClient;
use PHPUnit\Framework\TestCase;

/**
 * Abstract class for setup Ton client
 */
abstract class AbstractModuleTest extends TestCase
{
    protected TonClient $tonClient;

    public function setUp(): void
    {
        $this->tonClient = $this->getTonClient();
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
