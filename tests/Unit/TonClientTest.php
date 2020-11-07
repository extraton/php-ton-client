<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Entity\Client\ResultOfBuildInfo;
use Extraton\TonClient\Entity\Client\ResultOfGetApiReference;
use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\TonClient;
use GuzzleHttp\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function microtime;
use function uniqid;

class TonClientTest extends TestCase
{
    /** @var MockObject|TonClient */
    private MockObject $mockTonClient;

    /** @var MockObject|Promise */
    private MockObject $mockPromise;

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
    }

    public function testVersionWithSuccessResult(): void
    {
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
            ->with('client.version', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfVersion($response);

        self::assertEquals($expected, $this->mockTonClient->version());
    }

    public function testBuildInfoWithSuccessResult(): void
    {
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
            ->with('client.build_info', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfBuildInfo($response);

        self::assertEquals($expected, $this->mockTonClient->buildInfo());
    }

    public function testGetApiReferenceWithSuccessResult(): void
    {
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
            ->with('client.get_api_reference', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetApiReference($response);

        self::assertEquals($expected, $this->mockTonClient->getApiReference());
    }
}
