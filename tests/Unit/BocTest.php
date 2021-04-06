<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Boc;
use Extraton\TonClient\Entity\Boc\BuilderOp;
use Extraton\TonClient\Entity\Boc\CacheType;
use Extraton\TonClient\Entity\Boc\ResultOfBocCacheGet;
use Extraton\TonClient\Entity\Boc\ResultOfBocCacheSet;
use Extraton\TonClient\Entity\Boc\ResultOfEncodeBoc;
use Extraton\TonClient\Entity\Boc\ResultOfGetBlockchainConfig;
use Extraton\TonClient\Entity\Boc\ResultOfGetBocHash;
use Extraton\TonClient\Entity\Boc\ResultOfGetCodeFromTvc;
use Extraton\TonClient\Entity\Boc\ResultOfParse;
use Extraton\TonClient\Handler\Response;

use function microtime;
use function random_int;
use function uniqid;

/**
 * Unit tests for Boc module
 *
 * @coversDefaultClass \Extraton\TonClient\Boc
 */
class BocTest extends AbstractModuleTest
{
    private Boc $boc;

    public function setUp(): void
    {
        parent::setUp();
        $this->boc = new Boc($this->mockTonClient);
    }

    /**
     * @covers ::parseMessage
     */
    public function testParseMessage(): void
    {
        $boc = uniqid(microtime(), true);
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
                'boc.parse_message',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($response);

        self::assertEquals($expected, $this->boc->parseMessage($boc));
    }

    /**
     * @covers ::parseTransaction
     */
    public function testParseTransaction(): void
    {
        $boc = uniqid(microtime(), true);
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
                'boc.parse_transaction',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($response);

        self::assertEquals($expected, $this->boc->parseTransaction($boc));
    }

    /**
     * @covers ::parseAccount
     */
    public function testParseAccount(): void
    {
        $boc = uniqid(microtime(), true);
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
                'boc.parse_account',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($response);

        self::assertEquals($expected, $this->boc->parseAccount($boc));
    }

    /**
     * @covers ::parseBlock
     */
    public function testParseBlock(): void
    {
        $boc = uniqid(microtime(), true);
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
                'boc.parse_block',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($response);

        self::assertEquals($expected, $this->boc->parseBlock($boc));
    }

    /**
     * @covers ::getBlockchainConfig
     */
    public function testGetBlockchainConfig(): void
    {
        $blockBoc = uniqid(microtime(), true);
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
                'boc.get_blockchain_config',
                [
                    'block_boc' => $blockBoc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetBlockchainConfig($response);

        self::assertEquals($expected, $this->boc->getBlockchainConfig($blockBoc));
    }

    /**
     * @covers ::parseShardstate
     */
    public function testParseShardstate(): void
    {
        $boc = uniqid(microtime(), true);
        $id = uniqid(microtime(), true);
        $workchainId = random_int(0, 1000);
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
                'boc.parse_shardstate',
                [
                    'boc'          => $boc,
                    'id'           => $id,
                    'workchain_id' => $workchainId,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($response);

        self::assertEquals($expected, $this->boc->parseShardstate($boc, $id, $workchainId));
    }

    /**
     * @covers ::getBocHash
     */
    public function testGetBocHash(): void
    {
        $boc = uniqid(microtime(), true);
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
                'boc.get_boc_hash',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetBocHash($response);

        self::assertEquals($expected, $this->boc->getBocHash($boc));
    }

    /**
     * @covers ::getCodeFromTvc
     */
    public function testGetCodeFromTvc(): void
    {
        $tvc = uniqid(microtime(), true);
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
                'boc.get_code_from_tvc',
                [
                    'tvc' => $tvc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetCodeFromTvc($response);

        self::assertEquals($expected, $this->boc->getCodeFromTvc($tvc));
    }

    /**
     * @covers ::cacheGet
     */
    public function testCacheGet(): void
    {
        $bocRef = uniqid(microtime(), true);
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
                'boc.cache_get',
                [
                    'boc_ref' => $bocRef,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfBocCacheGet($response);

        self::assertEquals($expected, $this->boc->cacheGet($bocRef));
    }

    /**
     * @covers ::cacheSet
     */
    public function testCacheSet(): void
    {
        $boc = uniqid(microtime(), true);
        $cacheType = CacheType::fromPinned(uniqid(microtime(), true));
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
                'boc.cache_set',
                [
                    'boc'        => $boc,
                    'cache_type' => $cacheType,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfBocCacheSet($response);

        self::assertEquals($expected, $this->boc->cacheSet($boc, $cacheType));
    }

    /**
     * @covers ::cacheUnpin
     */
    public function testCacheUnpin(): void
    {
        $pin = uniqid(microtime(), true);
        $bocRef = uniqid(microtime(), true);
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
                'boc.cache_unpin',
                [
                    'pin'     => $pin,
                    'boc_ref' => $bocRef,
                ]
            )
            ->willReturn($this->mockPromise);

        $this->boc->cacheUnpin($pin, $bocRef);
    }

    /**
     * @covers ::encodeBoc
     */
    public function testEncodeBoc(): void
    {
        $builderOps = [
            BuilderOp::fromBitString(uniqid(microtime(), true)),
            BuilderOp::fromCellBoc(uniqid(microtime(), true)),
        ];
        $cacheType = CacheType::fromPinned(uniqid(microtime(), true));
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
                'boc.encode_boc',
                [
                    'builder'    => $builderOps,
                    'cache_type' => $cacheType,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfEncodeBoc($response);

        self::assertEquals($expected, $this->boc->encodeBoc($builderOps, $cacheType));
    }
}
