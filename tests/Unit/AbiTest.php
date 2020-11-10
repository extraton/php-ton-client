<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignature;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignatureToMessageBody;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeAccount;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessageBody;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Abi\StateInitSource;
use Extraton\TonClient\Handler\Response;
use PHPUnit\Framework\MockObject\MockObject;

use function microtime;
use function random_int;
use function uniqid;

use const PHP_INT_MAX;

/**
 * Unit tests for Abi module
 *
 * @coversDefaultClass \Extraton\TonClient\Abi
 */
class AbiTest extends AbstractModuleTest
{
    private Abi $abi;

    /** @var MockObject|AbiParams */
    private MockObject $mockAbi;

    /** @var MockObject|SignerParams */
    private MockObject $mockSigner;

    /** @var MockObject|DeploySetParams */
    private MockObject $mockDeploySet;

    /** @var MockObject|CallSetParams */
    private MockObject $mockCallSet;

    /** @var MockObject|StateInitSource */
    private MockObject $mockStateInitSource;

    public function setUp(): void
    {
        parent::setUp();
        $this->abi = new Abi($this->mockTonClient);

        $this->mockAbi = $this->getMockBuilder(AbiParams::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockSigner = $this->getMockBuilder(SignerParams::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockDeploySet = $this->getMockBuilder(DeploySetParams::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockCallSet = $this->getMockBuilder(CallSetParams::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockStateInitSource = $this->getMockBuilder(StateInitSource::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers ::encodeMessageBody
     */
    public function testEncodeMessageBodyWithSuccessResult(): void
    {
        $isInternal = (bool)random_int(0, 1);
        $processingTryIndex = random_int(0, PHP_INT_MAX);

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
                'abi.encode_message_body',
                [
                    'abi'                  => $this->mockAbi,
                    'call_set'             => $this->mockCallSet,
                    'signer'               => $this->mockSigner,
                    'is_internal'          => $isInternal,
                    'processing_try_index' => $processingTryIndex,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfEncodeMessageBody($response);

        self::assertEquals(
            $expected,
            $this->abi->encodeMessageBody(
                $this->mockAbi,
                $this->mockSigner,
                $this->mockCallSet,
                $isInternal,
                $processingTryIndex
            )
        );
    }

    /**
     * @covers ::attachSignatureToMessageBody
     */
    public function testAttachSignatureToMessageBodyWithSuccessResult(): void
    {
        $publicKey = uniqid(microtime(), true);
        $message = uniqid(microtime(), true);
        $signature = uniqid(microtime(), true);

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
                'abi.attach_signature_to_message_body',
                [
                    'abi'        => $this->mockAbi,
                    'public_key' => $publicKey,
                    'message'    => $message,
                    'signature'  => $signature,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfAttachSignatureToMessageBody($response);

        self::assertEquals(
            $expected,
            $this->abi->attachSignatureToMessageBody(
                $this->mockAbi,
                $publicKey,
                $message,
                $signature
            )
        );
    }

    /**
     * @covers ::encodeMessage
     */
    public function testEncodeMessageWithSuccessResult(): void
    {
        $address = uniqid(microtime(), true);
        $processingTryIndex = random_int(0, PHP_INT_MAX);

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
                'abi.encode_message',
                [
                    'abi'                  => $this->mockAbi,
                    'signer'               => $this->mockSigner,
                    'address'              => $address,
                    'deploy_set'           => $this->mockDeploySet,
                    'call_set'             => $this->mockCallSet,
                    'processing_try_index' => $processingTryIndex,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfEncodeMessage($response);

        self::assertEquals(
            $expected,
            $this->abi->encodeMessage(
                $this->mockAbi,
                $this->mockSigner,
                $address,
                $this->mockDeploySet,
                $this->mockCallSet,
                $processingTryIndex
            )
        );
    }

    /**
     * @covers ::attachSignature
     */
    public function testAttachSignatureWithSuccessResult(): void
    {
        $publicKey = uniqid(microtime(), true);
        $message = uniqid(microtime(), true);
        $signature = uniqid(microtime(), true);

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
                'abi.attach_signature',
                [
                    'abi'        => $this->mockAbi,
                    'public_key' => $publicKey,
                    'message'    => $message,
                    'signature'  => $signature,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfAttachSignature($response);

        self::assertEquals(
            $expected,
            $this->abi->attachSignature(
                $this->mockAbi,
                $publicKey,
                $message,
                $signature
            )
        );
    }

    /**
     * @covers ::decodeMessage
     */
    public function testDecodeMessageWithSuccessResult(): void
    {
        $message = uniqid(microtime(), true);

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
                'abi.decode_message',
                [
                    'abi'     => $this->mockAbi,
                    'message' => $message,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new DecodedMessageBody($response);

        self::assertEquals(
            $expected,
            $this->abi->decodeMessage(
                $this->mockAbi,
                $message,
            )
        );
    }

    /**
     * @covers ::decodeMessageBody
     */
    public function testDecodeMessageBodyWithSuccessResult(): void
    {
        $body = uniqid(microtime(), true);
        $isInternal = (bool)random_int(0, 1);

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
                'abi.decode_message_body',
                [
                    'abi'         => $this->mockAbi,
                    'body'        => $body,
                    'is_internal' => $isInternal,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new DecodedMessageBody($response);

        self::assertEquals(
            $expected,
            $this->abi->decodeMessageBody(
                $this->mockAbi,
                $body,
                $isInternal
            )
        );
    }

    /**
     * @covers ::encodeAccount
     */
    public function testEncodeAccountWithSuccessResult(): void
    {
        $balance = random_int(0, PHP_INT_MAX);
        $lastTransLt = random_int(0, PHP_INT_MAX);
        $lastPaid = random_int(0, PHP_INT_MAX);

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
                'abi.encode_account',
                [
                    'state_init'    => $this->mockStateInitSource,
                    'balance'       => $balance,
                    'last_trans_lt' => $lastTransLt,
                    'last_paid'     => $lastPaid,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfEncodeAccount($response);

        self::assertEquals(
            $expected,
            $this->abi->encodeAccount(
                $this->mockStateInitSource,
                $balance,
                $lastTransLt,
                $lastPaid
            )
        );
    }
}
