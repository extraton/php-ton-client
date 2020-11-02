<?php

declare(strict_types=1);

namespace Tests\Extraton\TonClient;

use Extraton\TonClient\Result\Client\ResultOfBuildInfo;
use Extraton\TonClient\Result\Client\ResultOfGetApiReference;
use Extraton\TonClient\Result\Client\ResultOfVersion;
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

    public function testGetVersionWithSuccessResult(): void
    {
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with('client.version', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfVersion($result);

        self::assertEquals($expected, $this->mockTonClient->getVersion());
    }

    public function testGetBuildInfoWithSuccessResult(): void
    {
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with('client.build_info', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfBuildInfo($result);

        self::assertEquals($expected, $this->mockTonClient->getBuildInfo());
    }

    public function testGetApiReferenceWithSuccessResult(): void
    {
        $result = [uniqid(microtime(), true)];

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with('client.get_api_reference', [])
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetApiReference($result);

        self::assertEquals($expected, $this->mockTonClient->getApiReference());
    }
}
