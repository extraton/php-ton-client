<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Exception\SDKException;
use Extraton\TonClient\Handler\Response;

/**
 * Integration tests for Processing module
 *
 * @coversDefaultClass \Extraton\TonClient\Processing
 */
class ProcessingTest extends AbstractModuleTest
{
    /**
     * @covers ::processMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testProcessMessage(): void
    {
        $abi = AbiType::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySet($this->dataProvider->getEventsTvc());
        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = Signer::fromKeys($keyPair);
        $callSet = (new CallSet('constructor'))->withFunctionHeaderParams($keyPair->getPublic());

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendTons($address);

        $expected = new ResultOfProcessMessage(
            new Response(
                [
                    'transaction'  =>
                        [
                            // .. more data lines
                            'status'      => 3,
                            'status_name' => 'finalized',
                            // .. more data lines
                        ],
                    'out_messages' =>
                        [],
                    'decoded'      =>
                        [
                            'out_messages' =>
                                [],
                            'output'       => null,
                        ],
                    'fees'         =>
                        [
                            // .. more data lines
                        ],

                ]
            )
        );

        $result = $this->processing->processMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        self::assertGreaterThan(
            0,
            $result->getTransactionFees()->getTotalAccountFees()
        );

        self::assertEquals(
            $expected->getOutMessages(),
            $result->getOutMessages()
        );

        self::assertEquals(
            $expected->getDecoded(),
            $result->getDecoded()
        );

        self::assertEquals(
            $expected->getTransaction()['status'],
            $result->getTransaction()['status']
        );

        self::assertEquals(
            $expected->getTransaction()['status_name'],
            $result->getTransaction()['status_name']
        );

        // Test contract execution error
        $callSet = (new CallSet('returnValue'))
            ->withInput(
                [
                    'id' => -1
                ]
            );

        $this->expectExceptionObject(
            SDKException::create(
                [
                    'code'    => 305,
                    'message' => "Encode deploy message failed: Wrong data format:\n-1",
                ]
            )
        );

        $this->processing->processMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet,
            $address
        );
    }

    /**
     * @covers ::processMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testProcessMessageWithEvents(): void
    {
        $abi = AbiType::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySet($this->dataProvider->getEventsTvc());

        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = Signer::fromKeys($keyPair);

        $callSet = (new CallSet('constructor'))
            ->withFunctionHeaderParams($keyPair->getPublic());

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendTons($address);

        $expected = new ResultOfProcessMessage(
            new Response(
                [
                    'transaction'  =>
                        [
                            // .. more data lines
                            'status'      => 3,
                            'status_name' => 'finalized',
                            // .. more data lines
                        ],
                    'out_messages' =>
                        [],
                    'decoded'      =>
                        [
                            'out_messages' =>
                                [],
                            'output'       => null,
                        ],
                    'fees'         =>
                        [
                            // .. more data lines
                        ],

                ]
            )
        );

        $resultOfProcessMessage = $this->processing->processMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet,
            null,
            null,
            true
        );

        foreach ($resultOfProcessMessage->getIterator() as $event) {
            self::assertContains(
                $event->getType(),
                [
                    'WillFetchFirstBlock',
                    'WillSend',
                    'DidSend',
                    'WillFetchNextBlock',
                ]
            );
        }

        self::assertGreaterThan(
            0,
            $resultOfProcessMessage->getTransactionFees()->getTotalAccountFees()
        );

        self::assertEquals(
            $expected->getOutMessages(),
            $resultOfProcessMessage->getOutMessages()
        );

        self::assertEquals(
            $expected->getDecoded(),
            $resultOfProcessMessage->getDecoded()
        );

        self::assertEquals(
            $expected->getTransaction()['status'],
            $resultOfProcessMessage->getTransaction()['status']
        );

        self::assertEquals(
            $expected->getTransaction()['status_name'],
            $resultOfProcessMessage->getTransaction()['status_name']
        );
    }

    /**
     * @covers ::waitForTransaction
     * @covers ::sendMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testWaitForTransaction(): void
    {
        $abi = AbiType::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySet($this->dataProvider->getEventsTvc());
        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = Signer::fromKeys($keyPair);
        $callSet = (new CallSet('constructor'))->withFunctionHeaderParams($keyPair->getPublic());

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendTons($address);

        $resultOfSendMessage = $this->processing->sendMessage(
            $resultOfEncodeMessage->getMessage(),
            false,
            $abi
        );

        $shardBlockId = $resultOfSendMessage->getShardBlockId();

        $expected = new ResultOfProcessMessage(
            new Response(
                [
                    'transaction'  =>
                        [
                            // more
                            'status'      => 3,
                            'status_name' => 'finalized',
                            // .. more data lines
                        ],
                    'out_messages' => [],
                    'decoded'      =>
                        [
                            'out_messages' => [],
                            'output'       => null,
                        ],
                    'fees'         =>
                        [
                            // .. more data lines
                        ],
                ]
            )
        );

        $resultOfProcessMessage = $this->processing->waitForTransaction(
            $resultOfEncodeMessage->getMessage(),
            $shardBlockId,
            false,
            $abi
        );

        self::assertGreaterThan(
            0,
            $resultOfProcessMessage->getTransactionFees()->getTotalAccountFees()
        );

        self::assertEquals(
            $expected->getOutMessages(),
            $resultOfProcessMessage->getOutMessages()
        );

        self::assertEquals(
            $expected->getDecoded(),
            $resultOfProcessMessage->getDecoded()
        );

        self::assertEquals(
            $expected->getTransaction()['status'],
            $resultOfProcessMessage->getTransaction()['status']
        );

        self::assertEquals(
            $expected->getTransaction()['status_name'],
            $resultOfProcessMessage->getTransaction()['status_name']
        );
    }

    /**
     * @covers ::waitForTransaction
     * @covers ::sendMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testWaitForTransactionWithEvents(): void
    {
        $abi = AbiType::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySet($this->dataProvider->getEventsTvc());
        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = Signer::fromKeys($keyPair);
        $callSet = (new CallSet('constructor'))->withFunctionHeaderParams($keyPair->getPublic());

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendTons($address);

        $resultOfSendMessage = $this->processing->sendMessage(
            $resultOfEncodeMessage->getMessage(),
            true,
            $abi
        );

        foreach ($resultOfSendMessage->getIterator() as $event) {
            self::assertContains(
                $event->getType(),
                [
                    'WillFetchFirstBlock',
                    'WillSend',
                    'DidSend'
                ]
            );
        }

        $shardBlockId = $resultOfSendMessage->getShardBlockId();

        $expected = new ResultOfProcessMessage(
            new Response(
                [
                    'transaction'  =>
                        [
                            // more
                            'status'      => 3,
                            'status_name' => 'finalized',
                            // .. more data lines
                        ],
                    'out_messages' => [],
                    'decoded'      =>
                        [
                            'out_messages' => [],
                            'output'       => null,
                        ],
                    'fees'         =>
                        [
                            // .. more data lines
                        ],
                ]
            )
        );

        $resultOfProcessMessage = $this->processing->waitForTransaction(
            $resultOfEncodeMessage->getMessage(),
            $shardBlockId,
            true,
            $abi
        );

        foreach ($resultOfProcessMessage->getIterator() as $event) {
            self::assertContains(
                $event->getType(),
                [
                    'WillFetchNextBlock',
                ]
            );
        }

        self::assertGreaterThan(
            0,
            $resultOfProcessMessage->getTransactionFees()->getTotalAccountFees()
        );

        self::assertEquals(
            $expected->getOutMessages(),
            $resultOfProcessMessage->getOutMessages()
        );

        self::assertEquals(
            $expected->getDecoded(),
            $resultOfProcessMessage->getDecoded()
        );

        self::assertEquals(
            $expected->getTransaction()['status'],
            $resultOfProcessMessage->getTransaction()['status']
        );

        self::assertEquals(
            $expected->getTransaction()['status_name'],
            $resultOfProcessMessage->getTransaction()['status_name']
        );
    }
}
