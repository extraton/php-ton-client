<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Boc;
use Extraton\TonClient\Request\Boc\ResultOfParse;
use Extraton\TonClient\TonClient;
use GuzzleHttp\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function microtime;
use function uniqid;

class BocTest extends TestCase
{
    /** @var MockObject|TonClient */
    private MockObject $mockTonClient;

    /** @var MockObject|Promise */
    private MockObject $mockPromise;

    private Boc $boc;

    public function setUp(): void
    {
        $this->mockTonClient = $this->getMockBuilder(TonClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'request'
                ]
            )
            ->getMock();

        $this->mockPromise = $this->getMockBuilder(Promise::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'wait'
                ]
            )
            ->getMock();

        $this->boc = new Boc($this->mockTonClient);
    }

    public function testParseMessageSuccessResult(): void
    {
        $boc = uniqid(microtime(), true);
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'boc.parse_message',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($result);

        self::assertEquals($expected, $this->boc->parseMessage($boc));
    }

    public function testParseTransactionSuccessResult(): void
    {
        $boc = uniqid(microtime(), true);
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'boc.parse_transaction',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($result);

        self::assertEquals($expected, $this->boc->parseTransaction($boc));
    }

    public function testParseAccountSuccessResult(): void
    {
        $boc = uniqid(microtime(), true);
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'boc.parse_account',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($result);

        self::assertEquals($expected, $this->boc->parseAccount($boc));
    }

    public function testParseBlockSuccessResult(): void
    {
        $boc = uniqid(microtime(), true);
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'boc.parse_block',
                [
                    'boc' => $boc,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfParse($result);

        self::assertEquals($expected, $this->boc->parseBlock($boc));
    }
}
