<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\FunctionHeaderParams;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Exception\RequestException;
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
        $abi = AbiParams::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySetParams($this->dataProvider->getEventsTvc());
        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = SignerParams::fromKeys($keyPair);

        $functionHeader = new FunctionHeaderParams($keyPair->getPublic());

        $callSet = new CallSetParams(
            'constructor',
            $functionHeader
        );

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendGrams($address);

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
        $callSet = new CallSetParams('returnValue', null, ['id' => -1]);

        $this->expectExceptionObject(
            RequestException::create(
                [
                    'code'    => 305,
                    'message' => "Encode deploy message failed: Wrong data format:\n-1",
                    'data'    =>
                        [
                            'core_version' => '1.1.0',
                        ],
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
        $abi = AbiParams::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySetParams($this->dataProvider->getEventsTvc());

        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = SignerParams::fromKeys($keyPair);

        $functionHeaderParams = new FunctionHeaderParams($keyPair->getPublic());

        $callSet = new CallSetParams('constructor', $functionHeaderParams);

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendGrams($address);

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

        self::assertGreaterThan(0, $resultOfProcessMessage->getFees()->getTotalAccountFees());
    }

    /**
     * @covers ::waitForTransaction
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testWaitForTransaction(): void
    {
        $abi = AbiParams::fromArray($this->dataProvider->getEventsAbiArray());
        $deploySet = new DeploySetParams($this->dataProvider->getEventsTvc());
        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $signer = SignerParams::fromKeys($keyPair);

        $functionHeader = new FunctionHeaderParams($keyPair->getPublic());

        $callSet = new CallSetParams(
            'constructor',
            $functionHeader
        );

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        $this->dataProvider->sendGrams($address);

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
}
