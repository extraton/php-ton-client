<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Boc;
use Extraton\TonClient\Entity\Boc\ResultOfParse;
use Extraton\TonClient\Handler\Response;

use function microtime;
use function uniqid;

class BocTest extends AbstractModuleTest
{
    private Boc $boc;

    public function setUp(): void
    {
        parent::setUp();
        $this->boc = new Boc($this->mockTonClient);
    }

    public function testParseMessageSuccessResult(): void
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

    public function testParseTransactionSuccessResult(): void
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

    public function testParseAccountSuccessResult(): void
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

    public function testParseBlockSuccessResult(): void
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
}
