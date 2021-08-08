<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignature;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignatureToMessageBody;
use Extraton\TonClient\Entity\Abi\ResultOfDecodeData;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeAccount;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeInternalMessage;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessageBody;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Abi\StateInitSource;
use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Handler\Response;
use PHPUnit\Framework\MockObject\MockObject;

use function microtime;
use function random_int;
use function time;
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

    /** @var MockObject|AbiType */
    private MockObject $mockAbi;

    /** @var MockObject|Signer */
    private MockObject $mockSigner;

    /** @var MockObject|DeploySet */
    private MockObject $mockDeploySet;

    /** @var MockObject|CallSet */
    private MockObject $mockCallSet;

    /** @var MockObject|StateInitSource */
    private MockObject $mockStateInitSource;

    public function setUp(): void
    {
        parent::setUp();
        $this->abi = new Abi($this->mockTonClient);

        $this->mockAbi = $this->getMockBuilder(AbiType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockSigner = $this->getMockBuilder(Signer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockDeploySet = $this->getMockBuilder(DeploySet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockCallSet = $this->getMockBuilder(CallSet::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockStateInitSource = $this->getMockBuilder(StateInitSource::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @covers ::encodeMessageBody
     */
    public function testEncodeMessageBody(): void
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
    public function testAttachSignatureToMessageBody(): void
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
    public function testEncodeMessage(): void
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
                $this->mockDeploySet,
                $this->mockCallSet,
                $address,
                $processingTryIndex
            )
        );
    }

    /**
     * @covers ::attachSignature
     */
    public function testAttachSignature(): void
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
    public function testDecodeMessage(): void
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
    public function testDecodeMessageBody(): void
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
    public function testEncodeAccount(): void
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

    /**
     * @covers ::encodeInternalMessage
     */
    public function testEncodeInternalMessage(): void
    {
        $value = uniqid(microtime(), true);
        $abi = AbiType::fromHandle(time());
        $address = uniqid(microtime(), true);
        $srcAddress = uniqid(microtime(), true);
        $deploySet = new DeploySet(uniqid(microtime(), true));
        $callSet = new CallSet(uniqid(microtime(), true));
        $bounce = (bool)random_int(0, 1);
        $enableIhr = (bool)random_int(0, 1);

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
                'abi.encode_internal_message',
                [
                    'value'       => $value,
                    'abi'         => $abi,
                    'address'     => $address,
                    'src_address' => $srcAddress,
                    'deploy_set'  => $deploySet,
                    'call_set'    => $callSet,
                    'bounce'      => $bounce,
                    'enable_ihr'  => $enableIhr,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfEncodeInternalMessage($response);

        self::assertEquals(
            $expected,
            $this->abi->encodeInternalMessage(
                $value,
                $abi,
                $address,
                $srcAddress,
                $deploySet,
                $callSet,
                $bounce,
                $enableIhr
            )
        );
    }

    /**
     * @covers ::decodeAccountData
     */
    public function testDecodeAccountData(): void
    {
        $abi = AbiType::fromHandle(time());
        $data = uniqid(microtime(), true);

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
                'abi.decode_account_data',
                [
                    'abi'  => $abi,
                    'data' => $data,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfDecodeData($response);

        self::assertEquals(
            $expected,
            $this->abi->decodeAccountData(
                $abi,
                $data,
            )
        );
    }
}
