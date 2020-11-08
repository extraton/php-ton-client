<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Utils;

use function microtime;
use function uniqid;

/**
 * Unit tests for Utils module
 *
 * @coversDefaultClass \Extraton\TonClient\Utils
 */
class UtilsTest extends AbstractModuleTest
{
    /** @var Utils */
    private Utils $utils;

    public function setUp(): void
    {
        parent::setUp();
        $this->utils = new Utils($this->mockTonClient);
    }

    /**
     * @covers ::convertAddress
     */
    public function testConvertAddressWithSuccessResult(): void
    {
        $address = uniqid(microtime(), true);
        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );
        $addressStringFormat = AddressStringFormat::accountId();

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

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

        $expected = new ResultOfConvertAddress($response);

        self::assertEquals($expected, $this->utils->convertAddress($address, $addressStringFormat));
    }
}
