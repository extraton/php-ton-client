<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Tvm\AccountForExecutor;

/**
 * Integration tests for Tvm module
 *
 * @coversDefaultClass \Extraton\TonClient\Tvm
 */
class TvmTest extends AbstractModuleTest
{
    /**
     * @covers ::runExecutor
     */
    public function testExecuteMessage(): void
    {
        $abi = AbiParams::fromArray($this->dataProvider->getSubscriptionAbiArray());

        $keyPair = $this->crypto->generateRandomSignKeys()->getKeyPair();
        $walletAddress = $this->dataProvider->getWalletAddress();

        // Deploy message
        $deploySet = new DeploySetParams($this->dataProvider->getSubscriptionTvc());
        $callSet = new CallSetParams(
            'constructor',
            null,
            [
                'wallet' => $walletAddress,
            ]
        );

        $signer = SignerParams::fromKeys($keyPair);

        // Get account deploy message
        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        $address = $resultOfEncodeMessage->getAddress();

        // Send tons
        $this->dataProvider->sendTons($address);

        // Deploy account
        $this->processing->processMessage(
            $abi,
            $signer,
            $deploySet,
            $callSet
        );

        // Get account data
        $query = (new ParamsOfWaitForCollection('accounts'))
            ->addFilter('id', Filters::EQ, $address)
            ->addResultField('id', 'boc');

        $resultOfWaitForCollection = $this->net->waitForCollection($query);

        $id = $resultOfWaitForCollection->getResult()['id'];
        $boc = $resultOfWaitForCollection->getResult()['boc'];

        // Get account balance
        $resultOfParse = $this->boc->parseAccount($boc);
        $balance = $resultOfParse->getParsed()['balance'];

        # Run executor with unlimited balance
        $callSet = new CallSetParams(
            'subscribe', null,
            [
                'subscriptionId' => $subscriptionId = '0x1111111111111111111111111111111111111111111111111111111111111111',
                'pubkey'         => $pubKey = '0x2222222222222222222222222222222222222222222222222222222222222222',
                'to'             => '0:3333333333333333333333333333333333333333333333333333333333333333',
                'value'          => '0x123',
                'period'         => '0x456'
            ]
        );

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            null,
            $callSet,
            $address
        );

        $messageId = $resultOfEncodeMessage->getMessageId();

        $accountForExecutor = AccountForExecutor::fromAccount($boc, true);

        $resultOfRunExecutor = $this->tvm->runExecutor(
            $resultOfEncodeMessage->getMessage(),
            $accountForExecutor,
            null,
            $abi
        );

        # Unlimited balance should not affect account balance
        $resultOfParse = $this->boc->parseAccount($resultOfRunExecutor->getAccount());
        self::assertEquals($balance, $resultOfParse->getParsed()['balance']);

        // Run executor with limited balance
        $accountForExecutor = AccountForExecutor::fromAccount($boc, false);
        $resultOfRunExecutor = $this->tvm->runExecutor(
            $resultOfEncodeMessage->getMessage(),
            $accountForExecutor,
            null,
            $abi
        );

        self::assertGreaterThan(0, $resultOfRunExecutor->getTransactionFees()->getTotalAccountFees());
        self::assertEquals($messageId, $resultOfRunExecutor->getTransaction()['in_msg']);

        // Check subscription
        $callSet = new CallSetParams(
            'getSubscription',
            null,
            [
                'subscriptionId' => $subscriptionId,
            ]
        );

        $resultOfEncodeMessage = $this->abi->encodeMessage(
            $abi,
            $signer,
            null,
            $callSet,
            $address
        );

        $resultOfRunTvm = $this->tvm->runTvm(
            $resultOfEncodeMessage->getMessage(),
            $resultOfRunExecutor->getAccount(),
            null,
            $abi
        );

        self::assertEquals($pubKey, $resultOfRunTvm->getDecodedOutput()->getOutput()['value0']['pubkey']);
    }
}
