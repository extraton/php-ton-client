<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Utils\ResultOfCalcStorageFee;
use Extraton\TonClient\Entity\Utils\ResultOfCompressZstd;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Entity\Utils\ResultOfDecompressZstd;
use Extraton\TonClient\Entity\Utils\ResultOfGetAddressType;
use Extraton\TonClient\Exception\SDKException;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\Handler\Response;
use Generator;

/**
 * Integration tests for Utils module
 *
 * @coversDefaultClass \Extraton\TonClient\Utils
 */
class UtilsTest extends AbstractModuleTest
{
    /**
     * @covers ::convertAddressToAccountId
     */
    public function testConvertAddressToAccountId(): void
    {
        $address = '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528';

        $expected = new ResultOfConvertAddress(
            new Response(
                [
                    'address' => 'ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
                ]
            )
        );

        self::assertEquals($expected, $this->utils->convertAddressToAccountId($address));
    }

    /**
     * @covers ::convertAddressToHex
     */
    public function testConvertAddressToHex(): void
    {
        $address = 'ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528';

        $expected = new ResultOfConvertAddress(
            new Response(
                [
                    'address' => '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
                ]
            )
        );

        self::assertEquals($expected, $this->utils->convertAddressToHex($address));
    }

    /**
     * @covers ::convertAddressToBase64
     *
     * @dataProvider dataForTestConvertAddressToBase64
     *
     * @param string $expectedAddress
     * @param string $sourceAddress
     * @param bool $url
     * @param bool $test
     * @param bool $bounce
     * @throws TonException
     */
    public function testConvertAddressToBase64(
        string $expectedAddress,
        string $sourceAddress,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ): void {
        $expected = new ResultOfConvertAddress(
            new Response(
                [
                    'address' => $expectedAddress,
                ]
            )
        );

        self::assertEquals(
            $expected,
            $this->utils->convertAddressToBase64(
                $sourceAddress,
                $url,
                $test,
                $bounce
            )
        );
    }

    /**
     * @return Generator
     */
    public function dataForTestConvertAddressToBase64(): Generator
    {
        yield [
            'kQDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KGjN',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            true,
            true
        ];

        yield [
            'kQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KGjN',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            true,
            true
        ];

        yield [
            'EQDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KNNH',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            false,
            true
        ];

        yield [
            '0QDuZdFwgwE2JTrYvSEWoo_L1KxGLG8iL0mhUF0vp_f1KDUI',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            true,
            true,
            false
        ];

        yield [
            'EQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KNNH',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            false,
            true
        ];

        yield [
            '0QDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KDUI',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            true,
            false
        ];

        yield [
            'UQDuZdFwgwE2JTrYvSEWoo/L1KxGLG8iL0mhUF0vp/f1KI6C',
            '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528',
            false,
            false,
            false
        ];
    }

    /**
     * @covers ::calcStorageFee
     */
    public function testCalcStorageFee(): void
    {
        $account = 'te6ccgECHQEAA/wAAnfAArtKDoOR5+qId/SCUGSSS9Qc4RD86X6TnTMjmZ4e+7EyOobmQvsHNngAAAg6t/34DgJWKJuuOehjU0ADAQFBlcBqp0PR+QAN1kt1SY8QavS350RCNNfeZ+ommI9hgd/gAgBToB6t2E3E7a7aW2YkvXv2hTmSWVRTvSYmCVdH4HjgZ4Z94AAAAAvsHNwwAib/APSkICLAAZL0oOGK7VNYMPShBgQBCvSkIPShBQAAAgEgCgcBAv8IAf5/Ie1E0CDXScIBn9P/0wD0Bfhqf/hh+Gb4Yo4b9AVt+GpwAYBA9A7yvdcL//hicPhjcPhmf/hh4tMAAY4SgQIA1xgg+QFY+EIg+GX5EPKo3iP4RSBukjBw3vhCuvLgZSHTP9MfNCD4I7zyuSL5ACD4SoEBAPQOIJEx3vLQZvgACQA2IPhKI8jLP1mBAQD0Q/hqXwTTHwHwAfhHbvJ8AgEgEQsCAVgPDAEJuOiY/FANAdb4QW6OEu1E0NP/0wD0Bfhqf/hh+Gb4Yt7RcG1vAvhKgQEA9IaVAdcLP3+TcHBw4pEgjjJfM8gizwv/Ic8LPzExAW8iIaQDWYAg9ENvAjQi+EqBAQD0fJUB1ws/f5NwcHDiAjUzMehfAyHA/w4AmI4uI9DTAfpAMDHIz4cgzo0EAAAAAAAAAAAAAAAAD3RMfijPFiFvIgLLH/QAyXH7AN4wwP+OEvhCyMv/+EbPCwD4SgH0AMntVN5/+GcBCbkWq+fwEAC2+EFujjbtRNAg10nCAZ/T/9MA9AX4an/4Yfhm+GKOG/QFbfhqcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EbPCwD4SgH0AMntVH/4ZwIBIBUSAQm7Fe+TWBMBtvhBbo4S7UTQ0//TAPQF+Gp/+GH4Zvhi3vpA1w1/ldTR0NN/39cMAJXU0dDSAN/RVHEgyM+FgMoAc89AzgH6AoBrz0DJc/sA+EqBAQD0hpUB1ws/f5NwcHDikSAUAISOKCH4I7ubIvhKgQEA9Fsw+GreIvhKgQEA9HyVAdcLP3+TcHBw4gI1MzHoXwb4QsjL//hGzwsA+EoB9ADJ7VR/+GcCASAYFgEJuORhh1AXAL74QW6OEu1E0NP/0wD0Bfhqf/hh+Gb4Yt7U0fhFIG6SMHDe+EK68uBl+AD4QsjL//hGzwsA+EoB9ADJ7VT4DyD7BCDQ7R7tU/ACMPhCyMv/+EbPCwD4SgH0AMntVH/4ZwIC2hsZAQFIGgAs+ELIy//4Rs8LAPhKAfQAye1U+A/yAAEBSBwAWHAi0NYCMdIAMNwhxwDcIdcNH/K8UxHdwQQighD////9vLHyfAHwAfhHbvJ8';
        $period = 1000;

        $expected = new ResultOfCalcStorageFee(
            new Response(
                [
                    'fee' => '330',
                ]
            )
        );

        self::assertEquals($expected, $this->utils->calcStorageFee($account, $period));
    }

    /**
     * @covers ::compressZstd
     */
    public function testCompressZstd(): void
    {
        $uncompressed = base64_encode(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        );
        $level = 21;

        $expected = new ResultOfCompressZstd(
            new Response(
                [
                    'compressed' => 'KLUv/QCAdQgAJhc5GJCnsA2AIm2tVzjno88mHb3Ttx9b8fXHHDAAMgAyAMUsVo6Pi3rPTDF2WDl510aHTwt44hrUxbn5oF6iUfiUiRbQhYo/PSM2WvKYt/hMIOQmuOaY/bmJQoRky46EF+cEd+Thsep5Hloo9DLCSwe1vFwcqIHycEKlMqBSo+szAiIBhkukH5kSIVlFukEWNF2SkIv6HBdPjFAjoUliCPjzKB/4jK91X95rTAKoASkPNqwUEw2Gkscdb3lR8YRYOR+P0sULCqzPQ8mQFJWnBSyP25mWIY2bFEUSJiGsWD+9NBqLhIAGDggQkLMbt5Y1aDR4uLKqwJXmQFPg/XTXIL7LCgspIF1YYplND4Uo',
                ]
            )
        );

        self::assertEquals($expected, $this->utils->compressZstd($uncompressed, $level));
    }

    /**
     * @covers ::decompressZstd
     */
    public function testDecompressZstd(): void
    {
        $compressed = 'KLUv/QCAdQgAJhc5GJCnsA2AIm2tVzjno88mHb3Ttx9b8fXHHDAAMgAyAMUsVo6Pi3rPTDF2WDl510aHTwt44hrUxbn5oF6iUfiUiRbQhYo/PSM2WvKYt/hMIOQmuOaY/bmJQoRky46EF+cEd+Thsep5Hloo9DLCSwe1vFwcqIHycEKlMqBSo+szAiIBhkukH5kSIVlFukEWNF2SkIv6HBdPjFAjoUliCPjzKB/4jK91X95rTAKoASkPNqwUEw2Gkscdb3lR8YRYOR+P0sULCqzPQ8mQFJWnBSyP25mWIY2bFEUSJiGsWD+9NBqLhIAGDggQkLMbt5Y1aDR4uLKqwJXmQFPg/XTXIL7LCgspIF1YYplND4Uo';

        $expected = new ResultOfDecompressZstd(
            new Response(
                [
                    'decompressed' => base64_encode(
                        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
                    ),
                ]
            )
        );

        self::assertEquals($expected, $this->utils->decompressZstd($compressed));
    }

    /**
     * @covers ::getAddressType
     * @dataProvider getAddressTypeData
     */
    public function testGetAddressType(
        string $address,
        bool $valid,
        ?string $type = null,
        ?bool $isHex = null,
        ?bool $isAccountId = null,
        ?bool $isBase64 = null
    ): void {
        if (!$valid) {
            self::expectException(SDKException::class);
        }

        $result = $this->utils->getAddressType($address);
        self::assertEquals($type, $result->getAddressType());
        self::assertEquals($isHex, $result->isHex());
        self::assertEquals($isBase64, $result->isBase64());
        self::assertEquals($isAccountId, $result->isAccountId());
    }

    /**
     * Data for testing getAddressType method
     *
     * @return Generator
     */
    public function getAddressTypeData(): Generator
    {
        yield [
            ' ',
            false,
        ];

        yield [
            '123456',
            false,
        ];

        yield [
            'abcdef',
            false,
        ];

        yield [
            '-1:7777777777777777777777777777777777777777777777777777777777777777',
            true,
            ResultOfGetAddressType::HEX,
            true,
            false,
            false
        ];

        yield [
            '0:919db8e740d50bf349df2eea03fa30c385d846b991ff5542e67098ee833fc7f7',
            true,
            ResultOfGetAddressType::HEX,
            true,
            false,
            false
        ];

        yield [
            '7777777777777777777777777777777777777777777777777777777777777777',
            true,
            ResultOfGetAddressType::ACCOUNT_ID,
            false,
            true,
            false
        ];

        yield [
            '919db8e740d50bf349df2eea03fa30c385d846b991ff5542e67098ee833fc7f7',
            true,
            ResultOfGetAddressType::ACCOUNT_ID,
            false,
            true,
            false
        ];

        yield [
            'EQCRnbjnQNUL80nfLuoD+jDDhdhGuZH/VULmcJjugz/H9wam',
            true,
            ResultOfGetAddressType::BASE64,
            false,
            false,
            true
        ];

        yield [
            'EQCRnbjnQNUL80nfLuoD-jDDhdhGuZH_VULmcJjugz_H9wam',
            true,
            ResultOfGetAddressType::BASE64,
            false,
            false,
            true
        ];

        yield [
            'UQCRnbjnQNUL80nfLuoD+jDDhdhGuZH/VULmcJjugz/H91tj',
            true,
            ResultOfGetAddressType::BASE64,
            false,
            false,
            true
        ];

        yield [
            'UQCRnbjnQNUL80nfLuoD-jDDhdhGuZH_VULmcJjugz_H91tj',
            true,
            ResultOfGetAddressType::BASE64,
            false,
            false,
            true
        ];
    }
}
