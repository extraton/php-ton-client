<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfCalcStorageFee;
use Extraton\TonClient\Entity\Utils\ResultOfCompressZstd;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Entity\Utils\ResultOfDecompressZstd;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Utils;

use function microtime;
use function time;
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
    public function testConvertAddress(): void
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

    /**
     * @covers ::calcStorageFee
     */
    public function testCalcStorageFee(): void
    {
        $account = uniqid(microtime(), true);
        $period = time();

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
                'utils.calc_storage_fee',
                [
                    'account' => $account,
                    'period'  => $period,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfCalcStorageFee($response);

        self::assertEquals($expected, $this->utils->calcStorageFee($account, $period));
    }

    /**
     * @covers ::compressZstd
     */
    public function testCompressZstd(): void
    {
        $uncompressed = uniqid(microtime(), true);
        $level = random_int(1, 21);

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
                'utils.compress_zstd',
                [
                    'uncompressed' => $uncompressed,
                    'level'        => $level,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfCompressZstd($response);

        self::assertEquals($expected, $this->utils->compressZstd($uncompressed, $level));
    }

    /**
     * @covers ::decompressZstd
     */
    public function testDecompressZstd(): void
    {
        $compressed = uniqid(microtime(), true);

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
                'utils.decompress_zstd',
                [
                    'compressed' => $compressed,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfDecompressZstd($response);

        self::assertEquals($expected, $this->utils->decompressZstd($compressed));
    }
}
