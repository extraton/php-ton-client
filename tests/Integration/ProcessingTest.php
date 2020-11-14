<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\Tests\Integration\TonClient\Data\DataProvider;
use Extraton\TonClient\Abi;
use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\FunctionHeaderParams;
use Extraton\TonClient\Entity\Abi\ParamsOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Exception\RequestException;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Processing;

/**
 * Integration tests for Processing module
 *
 * @coversDefaultClass \Extraton\TonClient\Processing
 */
class ProcessingTest extends AbstractModuleTest
{
    private Processing $processing;

    private Crypto $crypto;

    private Abi $abi;

    public function setUp(): void
    {
        parent::setUp();
        $this->processing = $this->tonClient->getProcessing();
        $this->crypto = $this->tonClient->getCrypto();
        $this->abi = $this->tonClient->getAbi();
    }

    /**
     * @covers ::processMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testProcessMessage(): void
    {
        $dataProvider = new DataProvider($this->tonClient);

        $abi = AbiParams::fromArray($dataProvider->getEventsAbiArray());
        $deploySet = new DeploySetParams($dataProvider->getEventsTvc());
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

        $dataProvider->sendGrams($address);

        $paramsOfEncodeMessage = new ParamsOfEncodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

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
                            'in_msg_fwd_fee'     => 9887000,
                            'storage_fee'        => 1,
                            'gas_fee'            => 4777000,
                            'out_msgs_fwd_fee'   => 0,
                            'total_account_fees' => 14664001,
                            'total_output'       => 0,
                        ],

                ]
            )
        );

        $result = $this->processing->processMessage(
            $paramsOfEncodeMessage,
            false
        );

        self::assertGreaterThan(0, $result->getTransactionFees()->getTotalAccountFees());
        self::assertEquals($expected->getOutMessages(), $result->getOutMessages());
        self::assertEquals($expected->getDecoded(), $result->getDecoded());
        self::assertEquals($expected->getTransaction()['status'], $result->getTransaction()['status']);
        self::assertEquals($expected->getTransaction()['status_name'], $result->getTransaction()['status_name']);

        // Test contract execution error
        $callSet = new CallSetParams('returnValue', null, ['id' => -1]);
        $paramsOfEncodeMessage = new ParamsOfEncodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet,
            $address
        );

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
            $paramsOfEncodeMessage,
            false
        );
    }

    /**
     * @covers ::processMessage
     * @covers \Extraton\TonClient\Abi::encodeMessage
     * @covers \Extraton\TonClient\Crypto::generateRandomSignKeys
     */
    public function testProcessMessageWithEvents(): void
    {
        $dataProvider = new DataProvider($this->tonClient);

        $abi = AbiParams::fromArray($dataProvider->getEventsAbiArray());
        $deploySet = new DeploySetParams($dataProvider->getEventsTvc());

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

        $dataProvider->sendGrams($address);

        $paramsOfEncodeMessage = new ParamsOfEncodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $resultOfProcessMessage = $this->processing->processMessage(
            $paramsOfEncodeMessage,
            true
        );

        foreach ($resultOfProcessMessage as $event) {
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
}
