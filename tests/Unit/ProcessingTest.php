<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Entity\Processing\ResultOfSendMessage;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Processing;

use function microtime;
use function random_int;
use function uniqid;

use const PHP_INT_MAX;

/**
 * Unit tests for Processing module
 *
 * @coversDefaultClass \Extraton\TonClient\Processing
 */
class ProcessingTest extends AbstractModuleTest
{
    private Processing $processing;

    public function setUp(): void
    {
        parent::setUp();
        $this->processing = new Processing($this->mockTonClient);
    }

    /**
     * @covers ::sendMessage
     */
    public function testSendMessage(): void
    {
        $message = uniqid(microtime(), true);
        $sendEvents = (bool)random_int(0, 1);
        $abi = AbiType::fromArray([]);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'processing.send_message',
                [
                    'message'     => $message,
                    'send_events' => $sendEvents,
                    'abi'         => $abi,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSendMessage($response);

        self::assertEquals(
            $expected,
            $this->processing->sendMessage(
                $message,
                $sendEvents,
                $abi
            )
        );
    }

    /**
     * @covers ::waitForTransaction
     */
    public function testWaitForTransaction(): void
    {
        $message = uniqid(microtime(), true);
        $shardBlockId = uniqid(microtime(), true);
        $sendEvents = (bool)random_int(0, 1);
        $abi = AbiType::fromArray([]);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'processing.wait_for_transaction',
                [
                    'message'        => $message,
                    'shard_block_id' => $shardBlockId,
                    'send_events'    => $sendEvents,
                    'abi'            => $abi,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfProcessMessage($response);

        self::assertEquals(
            $expected,
            $this->processing->waitForTransaction(
                $message,
                $shardBlockId,
                $sendEvents,
                $abi
            )
        );
    }

    /**
     * @covers ::processMessage
     */
    public function testProcessMessage(): void
    {
        $abi = AbiType::fromArray([]);
        $signer = Signer::fromNone();
        $deploySet = new DeploySet(uniqid(microtime(), true));
        $callSet = new CallSet(uniqid(microtime(), true));
        $address = uniqid(microtime(), true);
        $processingTryIndex = random_int(0, PHP_INT_MAX);
        $sendEvents = (bool)random_int(0, 1);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'processing.process_message',
                [
                    'message_encode_params' => [
                        'abi'                  => $abi,
                        'signer'               => $signer,
                        'deploy_set'           => $deploySet,
                        'call_set'             => $callSet,
                        'address'              => $address,
                        'processing_try_index' => $processingTryIndex,
                    ],
                    'send_events'           => $sendEvents,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfProcessMessage($response);

        self::assertEquals(
            $expected,
            $this->processing->processMessage(
                $abi,
                $signer,
                $deploySet,
                $callSet,
                $address,
                $processingTryIndex,
                $sendEvents,
            )
        );
    }
}
