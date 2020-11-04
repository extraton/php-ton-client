<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Request\Utils\AddressStringFormat;
use Extraton\TonClient\Request\Utils\ResultOfConvertAddress;
use Extraton\TonClient\TonClient;
use Extraton\TonClient\Utils;
use GuzzleHttp\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function microtime;
use function uniqid;

class UtilsTest extends TestCase
{
    /** @var MockObject|TonClient */
    private MockObject $mockTonClient;

    /** @var MockObject|Promise */
    private MockObject $mockPromise;

    /** @var Utils */
    private Utils $utils;

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

        $this->utils = new Utils($this->mockTonClient);
    }

    public function testConvertAddressSuccessResult(): void
    {
        $address = uniqid(microtime(), true);
        $result = [uniqid(microtime(), true)];
        $addressStringFormat = AddressStringFormat::accountId();

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'utils.convert_address',
                [
                    'address'       => $address,
                    'output_format' => $addressStringFormat,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfConvertAddress($result);

        self::assertEquals($expected, $this->utils->convertAddress($address, $addressStringFormat));
    }
}
