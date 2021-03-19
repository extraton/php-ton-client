<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Entity\Utils\ResultOfCalcStorageFee;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
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
     * @dataProvider dataForTestConvertAddressToBase64WithSuccessResult
     *
     * @param string $expectedAddress
     * @param string $sourceAddress
     * @param bool $url
     * @param bool $test
     * @param bool $bounce
     * @throws TonException
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

    /**
     * @covers ::calcStorageFee
     */
    public function testCalcStorageFeeWithSuccessResult(): void
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
}
