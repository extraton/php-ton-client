<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Utils;
use Generator;

/**
 * Integration tests for Utils module
 *
 * @coversDefaultClass \Extraton\TonClient\Utils
 */
class UtilsTest extends AbstractModuleTest
{
    private Utils $utils;

    public function setUp(): void
    {
        parent::setUp();
        $this->utils = $this->tonClient->getUtils();
    }

    /**
     * @covers ::convertAddressToAccountId
     */
    public function testConvertAddressToAccountIdWithSuccessResult(): void
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
    public function testConvertAddressToHexWithSuccessResult(): void
    {
        $address = '0:ee65d170830136253ad8bd2116a28fcbd4ac462c6f222f49a1505d2fa7f7f528';

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
     * @dataProvider dataForTestConvertAddressToBase64WithSuccessResult
     *
     * @param string $expectedAddress
     * @param string $sourceAddress
     * @param bool $url
     * @param bool $test
     * @param bool $bounce
     */
    public function testConvertAddressToBase64WithSuccessResult(
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
    public function dataForTestConvertAddressToBase64WithSuccessResult(): Generator
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
}
