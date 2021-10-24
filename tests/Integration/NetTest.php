<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Net\Aggregation;
use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\MessageNode;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfAggregateCollection;
use Extraton\TonClient\Entity\Net\ParamsOfBatchQuery;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCounterparties;
use Extraton\TonClient\Entity\Net\ResultOfQueryTransactionTree;
use Extraton\TonClient\Entity\Net\ResultOfWaitForCollection;
use Extraton\TonClient\Entity\Net\TransactionNode;
use Extraton\TonClient\Handler\Response;

use function dechex;
use function hexdec;

/**
 * Integration tests for Net module
 *
 * @coversDefaultClass \Extraton\TonClient\Net
 */
class NetTest extends AbstractModuleTest
{
    /**
     * @covers ::queryCollection
     */
    public function testQueryCollection(): void
    {
        $query = new ParamsOfQueryCollection(
            'accounts',
            [
                'acc_type',
                'acc_type_name',
                'balance',
                'boc',
                'code',
                'code_hash',
                'data',
                'data_hash',
                'due_payment',
                'id',
                'last_paid',
                'last_trans_lt',
                'library',
                'library_hash',
                'proof',
                'split_depth',
                'state_hash',
                'tick',
                'tock',
                'workchain_id',
            ],
            (new Filters())->add(
                'last_paid',
                Filters::IN,
                [
                    1626994845,
                    1626994874,
                    1626994901,
                    1626994923,
                    1626995009,
                    1626995043,
                    1626995129,
                ]
            ),
            (new OrderBy())->add(
                'last_paid',
                OrderBy::DESC
            ),
            2
        );

        $expected = new ResultOfQueryCollection(
            new Response(
                [
                    'result' => [
                        [
                            'acc_type'      => 1,
                            'acc_type_name' => 'Active',
                            'balance'       => '0x1c8f9985',
                            'boc'           => 'te6ccgECEQEAAsQAAm/ABjnj+rHjU74AcjblZ8fYnvVNPAEU9paoRsuv4l1MIyBSIoTCwwfPzcgAAAACA/AUCQcj5mFTQAIBAJXgn0u2wwDaiPu4oC24Agwi5VSxhWN5YYfDQ53BFcvz7gAAAXrQd3xugARd0XBwzDw3s7GnjkVUVm7//j5S5xc4X6U18WJpTs4z8MACJv8A9KQgIsABkvSg4YrtU1gw9KEHAwEK9KQg9KEEAgPOwAYFADfXaiaGn/6Z/pgGmH64X//DX8NT/8MPwzfDH8MUADf3whZGX//CHnhZ/8I2eFgHwlfCWBZYfl/+T2qkAgEgCggB5P9/Ie1E0CDXScIBjhjT/9M/0wDTD9cL//hr+Gp/+GH4Zvhj+GKOHvQFcPhqcPhrcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLTAAGOHYECANcYIPkBAdMAAZTT/wMBkwL4QuIg+GX5EPKoldMAAfJ64tM/AQkAfo4e+EMhuSCfMCD4I4ED6KiCCBt3QKC53pL4Y+CANPI02NMfIcEDIoIQ/////byxk1vyPOAB8AH4R26TMPI83gIBIA4LAgN9eA0MAH2xbiIv8ILdJeAJvaPwlEOB/xxGR6GmA/SAYGORnw5BnQDBnoGfA58Dnyd9uIi8Q54WH5Lj9gG8YSXgB7z/8M8ATbEQyqfwgt0l4Am9ph+j8IpA3SRg4b3wl3XlwMvwAEHw1GHgBv/wzwIBIBAPAOG7yR4cX4QW6OQ+1E0CDXScIBjhjT/9M/0wDTD9cL//hr+Gp/+GH4Zvhj+GKOHvQFcPhqcPhrcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtcN/5XU0dDT/9/RIMIA8uBk+AAg+Gsw8AN/+GeABg3XAi0NcLA6k4ANwhxwDcIdMfId0hwQMighD////9vLGTW/I84AHwAfhHbpMw8jze',
                            'code'          => 'te6ccgECDwEAAjsAAib/APSkICLAAZL0oOGK7VNYMPShBQEBCvSkIPShAgIDzsAEAwA312omhp/+mf6YBph+uF//w1/DU//DD8M3wx/DFAA398IWRl//wh54Wf/CNnhYB8JXwlgWWH5f/k9qpAIBIAgGAeT/fyHtRNAg10nCAY4Y0//TP9MA0w/XC//4a/hqf/hh+Gb4Y/hijh70BXD4anD4a3ABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwEHAH6OHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHyHBAyKCEP////28sZNb8jzgAfAB+EdukzDyPN4CASAMCQIDfXgLCgB9sW4iL/CC3SXgCb2j8JRDgf8cRkehpgP0gGBjkZ8OQZ0AwZ6BnwOfA58nfbiIvEOeFh+S4/YBvGEl4Ae8//DPAE2xEMqn8ILdJeAJvaYfo/CKQN0kYOG98Jd15cDL8ABB8NRh4Ab/8M8CASAODQDhu8keHF+EFujkPtRNAg10nCAY4Y0//TP9MA0w/XC//4a/hqf/hh+Gb4Y/hijh70BXD4anD4a3ABgED0DvK91wv/+GJw+GNw+GZ/+GHi3vhG8nNx+GbXDf+V1NHQ0//f0SDCAPLgZPgAIPhrMPADf/hngAYN1wItDXCwOpOADcIccA3CHTHyHdIcEDIoIQ/////byxk1vyPOAB8AH4R26TMPI83g==',
                            'code_hash'     => '6ceb662c4e5e827079984ce31e8c9230c677f3699de0a10631bfff0c986e2e98',
                            'data'          => 'te6ccgEBAQEATQAAleCfS7bDANqI+7igLbgCDCLlVLGFY3lhh8NDncEVy/PuAAABetB3fG6ABF3RcHDMPDezsaeORVRWbv/+PlLnFzhfpTXxYmlOzjPwwA==',
                            'data_hash'     => '16f1920161eb49f39cc096ee9f1d9ec1846167c191dbd8d39b4316f1b4e46c2c',
                            'due_payment'   => null,
                            'id'            => '0:639e3fab1e353be007236e567c7d89ef54d3c0114f696a846cbafe25d4c23205',
                            'last_paid'     => 1626995129,
                            'last_trans_lt' => '0x80fc0502',
                            'library'       => null,
                            'library_hash'  => null,
                            'proof'         => null,
                            'split_depth'   => null,
                            'state_hash'    => null,
                            'tick'          => null,
                            'tock'          => null,
                            'workchain_id'  => 0,
                        ],
                        [
                            'acc_type'      => 1,
                            'acc_type_name' => 'Active',
                            'balance'       => '0x12705d155',
                            'boc'           => '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',
                            'code'          => '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',
                            'code_hash'     => 'cf7e2a37fd6b66e889447bc9cc6ca315d50f17f5ad4bb3e7e947f4838afc9614',
                            'data'          => 'te6ccgECBAEAAV8AA9WbZSC0tdxftYO/KKADEWjcuNhC8rV/+lrfJ+x3HYzqtQAAAXrQdfq8gAJNspBaWu4v2sHflFABiLRuXGwheVq//S1vk/Y7jsZ1WoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIAMCAQBDgAjWbTKPUwvRGdZPAKqhIylhmeMD9IyLypvcl3MXJhpskADJgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEADes2e9qxG2oElgTVWtvVgr1xaJOVMpFBPdM5zED6FCegAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAw1XpwzsGWDncXf65zMbeY9FuTyCQ56tztfZs4RFMKRfgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAdbzRWAHeRIcgEOoCXKeQS+yULekX6tnQJNZk1coYLvBjbILGGw',
                            'data_hash'     => '501600ed05199722a514d4c71095cded248966748b72d1a901bd0dd06e5ec6fd',
                            'due_payment'   => null,
                            'id'            => '0:55e9c33b065839dc5dfeb9ccc6de63d16e4f2090e7ab73b5f66ce1114c2917e0',
                            'last_paid'     => 1626995043,
                            'last_trans_lt' => '0x7e7b2683',
                            'library'       => null,
                            'library_hash'  => null,
                            'proof'         => null,
                            'split_depth'   => null,
                            'state_hash'    => null,
                            'tick'          => null,
                            'tock'          => null,
                            'workchain_id'  => 0,
                        ],
                    ],
                ]
            )
        );

        self::assertEquals($expected, $this->net->queryCollection($query));
    }

    /**
     * @covers ::waitForCollection
     */
    public function testWaitForCollection(): void
    {
        $query = new ParamsOfWaitForCollection(
            'accounts',
            [
                'acc_type',
                'acc_type_name',
                'balance',
                'boc',
                'code',
                'code_hash',
                'data',
                'data_hash',
                'due_payment',
                'id',
                'last_paid',
                'last_trans_lt',
                'library',
                'library_hash',
                'proof',
                'split_depth',
                'state_hash',
                'tick',
                'tock',
                'workchain_id',
            ],
            (new Filters())->add(
                'last_paid',
                Filters::EQ,
                1626995009,
            ),
            90_000
        );

        $expected = new ResultOfWaitForCollection(
            new Response(
                [
                    'result' => [
                        'acc_type'      => 1,
                        'acc_type_name' => 'Active',
                        'balance'       => '0x126b1120f',
                        'boc' => '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',
                        'code' => 'te6ccgECSgEAEssAEiYgfcVgxZVt4aLBR5NW+PPucKWXZ9sr9HiLHWGtQs2tggAM/wD0pCAiwAGS9KDhiu1TWDD0oQUBAQr0pCD0oQICA83ABAMAb9O1E0NP/0z/TANP/0//0BPQE0wf0BNMf0wfXCwf4cvhx+HD4b/hu+G34bPhr+Gp/+GH4Zvhj+GKAHHz4QsjL//hDzws/+EbPCwD4SvhL+Ez4TfhO+E/4UPhR+FJegMv/y//0APQAywf0AMsfywfLB8ntVICASAIBgH0/38h7UTQINdJwgGONNP/0z/TANP/0//0BPQE0wf0BNMf0wfXCwf4cvhx+HD4b/hu+G34bPhr+Gp/+GH4Zvhj+GKOM/QFcPhqcPhrbfhsbfhtcPhubfhvcPhwcPhxcPhycAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLTAAEHAK6OHYECANcYIPkBAdMAAZTT/wMBkwL4QuIg+GX5EPKoldMAAfJ64tM/AY4e+EMhuSCfMCD4I4ED6KiCCBt3QKC53pL4Y+CANPI02NMfAfgjvPK50x8B8AECASAmCQIBIBgKAgEgDwsB47hiRe5fCC3SXgIb2i4NreBfBHan8CHCNDAEFZ8JkAgekNHDQDpn+mP6YPpg+n/6YP9IGm/6Yfqa4UAN4W/xxe4L7BGhDAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAI4OGRkuDeFuHFIkEAwBho6A6F8EIcD/ji4j0NMB+kAwMcjPhyDOjQQAAAAAAAAAAAAAAAAPMSL3KM8WIW8iAssf9ADJcfsA3jDA/5LwD95/+GcNAdJTI7yOQFNBbyvIK88LPyrPCx8pzwsHKM8LByfPC/8mzwsHJc8WJM8LfyPPCw8izxQhzwoAC18LAW8iIaQDWYAg9ENvAjXeIvhMgED0fI4aAdM/0x/TB9MH0//TB/pA03/TD9TXCgBvC38OAGyOL3BfYI0IYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABHBwyMlwbwtw4gI1MzECASAXEAIBahMRAUmxaPiv8ILdJeAhvaZ/qaPwikDdJGDhvfCbAgIB6BxBImO95cDJEgHsjoDYIfhPgED0DiCOGgHTP9MH0wfTH9P/0//TH/QEWW8CAdcLB28IkW3iIfLgcyL5ACFvFbry4HcgbxL4Ub7y4Hj4AFMwbxFxtR8hrIQfovhQsPhwMPhPgED0WzD4byL7BCLQ7R7tUyBvFiFvF/ACXwTwD3/4ZzMBB7A80nkUAf74QW6OdO1E0CDXScIBjjTT/9M/0wDT/9P/9AT0BNMH9ATTH9MH1wsH+HL4cfhw+G/4bvht+Gz4a/hqf/hh+Gb4Y/hijjP0BXD4anD4a234bG34bXD4bm34b3D4cHD4cXD4cnABgED0DvK91wv/+GJw+GNw+GZ/+GHi3vhG8nNxFQGe+GbTH/QEWW8CAdMH0fhFIG6SMHDe+EK68uBkIW8QwgAglzAhbxCAILve8uB1+ABccHAjbxGAIPQO8rLXC//4aiJvEHCaUwG5IJQwIsEg3hYAso4xUwRvEYAg9A7ystcL/yD4TYEBAPQOIJEx3o4TUzOkNSH4TVjIywdZgQEA9EP4bd8wpOgwUxK7kSGRIuL4ciFyu5EhlyGnAqRzqQTi+HEw+G5fBPAPf/hnANW3rhxDPhBbpLwEN7RdYAggQ4RgggPQkD4UvhRJsD/jj4o0NMB+kAwMcjPhyDOjQQAAAAAAAAAAAAAAAAOa4cQyM8WJs8LByXPCwckzws/I88LfyLPCwchzwsHyXH7AN5fBsD/kvAP3n/4Z4AIBICEZAgEgHhoCAWYdGwG9sAGws/CC3SXgIb2i4NreBfCbAgIB6Q0qA64WDv8m4ODhxSJBHGao5iWQRZ4WDkOeF/5iYgLeRENIBrMAQeiG3gRoRfCbAgIB6PkqA64WDv8m4ODhxARqZmPQvgZDgf8cAHaOLiPQ0wH6QDAxyM+HIM6NBAAAAAAAAAAAAAAAAA2wDYWYzxYhbyICyx/0AMlx+wDeMMD/kvAP3n/4ZwBfsMgZ6fCC3SXgIb2poxoI4AAAAAAAAAAAAAAAAD65TmRBkZxDnimS4/YAYeAe//DPAdm2JwNDfhBbpLwEN7RcG1vAnBw+EyAQPSGjhoB0z/TH9MH0wfT/9MH+kDTf9MP1NcKAG8Lf44vcF9gjQhgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEcHDIyXBvC3DiAjQwMZEggHwHmjmxfIsjLPwFvIiGkA1mAIPRDbwIzIfhMgED0fI4aAdM/0x/TB9MH0//TB/pA03/TD9TXCgBvC3+OL3BfYI0IYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABHBwyMlwbwtw4gI0MDHoWyHA/yAAdo4uI9DTAfpAMDHIz4cgzo0EAAAAAAAAAAAAAAAADQnA0NjPFiFvIgLLH/QAyXH7AN4wwP+S8A/ef/hnAgFuJSIBmLMedz74QW6S8BDe0XBtbwL4I7U/gQ4RoYAgrPhPgED0ho4bAdM/0wfTB9Mf0//T/9Mf9ARZbwIB1wsHbwh/mnBfcG1vAnBvCHDikSAjAfqOdVMjvI47U0FvKMgozws/J88LBybPCwclzwsfJM8L/yPPC/8ibyJZzwsf9AAhzwsHCF8IAW8iIaQDWYAg9ENvAjXeIvhPgED0fI4bAdM/0wfTB9Mf0//T/9Mf9ARZbwIB1wsHbwh/mnBfcG1vAnBvCHDiAjUzMehfBCHA/yQAdo4uI9DTAfpAMDHIz4cgzo0EAAAAAAAAAAAAAAAADPHnc+jPFiFvIgLLH/QAyXH7AN4wwP+S8A/ef/hnAOay7mRs+EFukvAQ3vpBldTR0PpA39cNf5XU0dDTf9/XDACV1NHQ0gDf1w0HldTR0NMH39TR+E7AAfLgbPhFIG6SMHDe+Eq68uBk+ABUc0LIz4WAygBzz0DOAfoCgGrPQM+DIc8UySL7AF8FwP+S8A/ef/hnEgErb0Xd6DHqdDQKIMwJqUH9biFlFKyvNPGQ2YBQOm3L7AAJICsnAcW6EiO6L4QW6S8BDe1w3/ldTR0NP/3yDHAZPU0dDe0x/0BFlvAgHXDQeV1NHQ0wff0XD4RSBukjBw3l8g+E2BAQD0DiCUAdcLB5Fw4gHy4GQxJG8QwgAglzAkbxCAILve8uB1goAvyOgNj4UF9BcbUfIqywwwBVMF8E8tBx+AD4UF8xcbUfIawisTIwMTH4cPgjtT+AIKz4JYIQ/////7CxM1MgcHAlXzpvCCP4T1hvKMgozws/J88LBybPCwclzwsfJM8L/yPPC/8ibyJZzwsf9AAhzwsHCF8IWYBA9EP4byJc+E8zKQH8gED0Do4Z0z/TB9MH0x/T/9P/0x/0BFlvAgHXCwdvCJlwX2BtbwJwbwjiIG8SpG9SIG8TInG1HyGsIrEyMG9TIvhPIm8oyCjPCz8nzwsHJs8LByXPCx8kzwv/I88L/yJvIlnPCx/0ACHPCwcIXwhZgED0Q/hvXwNVIl8FIcD/KgBmjioj0NMB+kAwMcjPhyDOjQQAAAAAAAAAAAAAAAAKEiO6KM8WIc8LP8lx+wDeMPAPf/hnAgEgQywCASA5LQIBIC8uAK218Chx6Y/pg+i4L5EvmLjaj5FWWGGAKqAvgqytkOB/xxUR6GmA/SAYGORnw5BnRoIAAAAAAAAAAAAAAAAE/wKHHGeLEOeFAGS4/YBvGGB/yXgH7z/8M8ACAVg1MAFXsSQDEfCC3SXgIb2mf6PwikDdJGDhvEHwmwICAegcQSgDrhYPIuHEA+XAyGMxAvyOgNgh+E+AQPQOII4aAdM/0wfTB9Mf0//T/9Mf9ARZbwIB1wsHbwiRbeIh8uBzIG8TI18xcbUfIqywwwBVMF8E8tB0+ABdIfhPgED0Do4Z0z/TB9MH0x/T/9P/0x/0BFlvAgHXCwdvCJlwX2BtbwJwbwjiIG8SpG9SIG8TInEzMgCOtR8hrCKxMjBvUyL4TyJvKMgozws/J88LBybPCwclzwsfJM8L/yPPC/8ibyJZzwsf9AAhzwsHCF8IWYBA9EP4b18H8A9/+GcBlvgjtT+BDhGhgCCs+E+AQPSGjhsB0z/TB9MH0x/T/9P/0x/0BFlvAgHXCwdvCH+acF9wbW8CcG8IcOJfIJQwUyO73iCSXwXh+ACRIDQAwI5XXW8RcbUfIayEH6L4ULD4cDD4T4BA9Fsw+G8j+E+AQPR8jhsB0z/TB9MH0x/T/9P/0x/0BFlvAgHXCwdvCH+acF9wbW8CcG8IcOICNjQyUxGUMFM0u94x6PAP+A9fBQFXsU6B2/CC3SXgIb2mf6PwikDdJGDhvEHwmwICAegcQSgDrhYPIuHEA+XAyGM2Ap6OgNgh+EyAQPQOII4ZAdM/0x/TB9MH0//TB/pA03/TD9TXCgBvC5Ft4iHy4GYgbxEjXzFxtR8irLDDAFUwXwTy0Gf4AFRzAiFvE6QibxK+QDcBho5BIW8XIm8WI28ayM+FgMoAc89AzgH6AoBqz0DPgyJvGc8UySJvGPsA+EsibxUhcXgjqKyhMTH4ayL4TIBA9Fsw+Gw4AL6OVSFvESFxtR8hrCKxMjAiAW9RMlMRbxOkb1MyIvhMI28ryCvPCz8qzwsfKc8LByjPCwcnzwv/Js8LByXPFiTPC38jzwsPIs8UIc8KAAtfC1mAQPRD+GziXwfwD3/4ZwFrtsdgs34QW6S8BDe+kGV1NHQ+kDf1w1/ldTR0NN/39cMAJXU0dDSAN/XDACV1NHQ0gDf1NFwgOgFyjoDYIcD/jioj0NMB+kAwMcjPhyDOjQQAAAAAAAAAAAAAAAAJMdgs2M8WIc8LP8lx+wDeMPAPf/hnOwGo+EUgbpIwcN5fIPhNgQEA9A4glAHXCweRcOIB8uBkMSaCCA9CQL7y4Gsj0G0BcHGOESLXSpRY1VqklQLXSaAB4iJu5lgwIYEgALkglDAgwQje8uB5PAKwjoDY+EtTMHgiqK2BAP+wtQcxMcEF8uBx+ABThnJxsSGbMHKBAICx+CdvEDPeUwJsMvhSIMABjiBUccrIz4WAygBzz0DOAfoCgGrPQM+DKc8UySP7AF8NcEA9AQqOgOME2T4B+PhLU2BxeCOorKAxMfhr+CO1P4AgrPglghD/////sLEgcCNwXytWE1OaVhJWFW8LXFOQbxOkIm8Svo5BIW8XIm8WI28ayM+FgMoAc89AzgH6AoBqz0DPgyJvGc8UySJvGPsA+EsibxUhcXgjqKyhMTH4ayL4TIBA9Fsw+Gw/ALqOVSFvESFxtR8hrCKxMjAiAW9RMlMRbxOkb1MyIvhMI28ryCvPCz8qzwsfKc8LByjPCwcnzwv/Js8LByXPFiTPC38jzwsPIs8UIc8KAAtfC1mAQPRD+GziXwMeXw4B8PgjtT+BDhGhgCCs+EyAQPSGjhoB0z/TH9MH0wfT/9MH+kDTf9MP1NcKAG8Lf44vcF9gjQhgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEcHDIyXBvC3DiXyCUMFMju94gkl8F4fgAcJhTEZQwIMEo3kEB/o59pPhLJG8VIXF4I6isoTEx+Gsk+EyAQPRbMPhsJPhMgED0fI4aAdM/0x/TB9MH0//TB/pA03/TD9TXCgBvC3+OL3BfYI0IYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABHBwyMlwbwtw4gI3NTNTIpQwU0W73jJCAA7o8A/4D18GAgEgRkQB37a2aCO+EFukvAQ3tM/0XBfUI0IYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABHBwyMlwbwsh+EyAQPQOII4ZAdM/0x/TB9MH0//TB/pA03/TD9TXCgBvC5Ft4iHy4GYgM1UCXwMhwP+BFALyOUSPQ0wH6QDAxyM+HIM6AYc9Az4PIz5IrZoI6Im8rVQorzws/Ks8LHynPCwcozwsHJ88L/ybPCwclzxYkzwt/I88LDyLPFCHPCgALXwvNyXH7AN4wwP+S8A/ef/hnAgLZSUcB/0cPhqcPhrbfhsbfhtcPhubfhvcPhwcPhxcPhyXHBwI28RgCD0DvKy1wv/+GoibxBwmlMBuSCUMCLBIN6OMVMEbxGAIPQO8rLXC/8g+E2BAQD0DiCRMd6OE1MzpDUh+E1YyMsHWYEBAPRD+G3fMKToMFMSu5EhkSLi+HIhcruRIYSACYlyGnAqRzqQTi+HEw+G5fBPhCyMv/+EPPCz/4Rs8LAPhK+Ev4TPhN+E74T/hQ+FH4Ul6Ay//L//QA9ADLB/QAyx/LB8sHye1U+A/yAABLRwItDWAjHSADDcIccA3CHXDR/dUxHdwQQighD////9vLHcAfABg=',
                        'code_hash'     => '207dc560c5956de1a2c1479356f8f3ee70a59767db2bf4788b1d61ad42cdad82',
                        'data'          => 'te6ccgEBAgEAmAAB35tlILS13F+1g78ooAMRaNy42ELytX/6Wt8n7HcdjOq1AAABetB1ihfNspBaWu4v2sHflFABiLRuXGwheVq//S1vk/Y7jsZ1WoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAICAAAAAAEBgBAEWgE2ykFpa7i/awd+UUAGItG5cbCF5Wr/9LW+T9juOxnVagEA==',
                        'data_hash'     => '7cf565c98e475e7f493cc6dcb0d7d2f4ec5fd3eb1aa215d50043d65c927430cf',
                        'due_payment'   => null,
                        'id'            => '0:ef2243900875012e53c825f64a16f48bf56ce8126b326ae50c177831b641630d',
                        'last_paid'     => 1626995009,
                        'last_trans_lt' => '0x7d77c042',
                        'library'       => null,
                        'library_hash'  => null,
                        'proof'         => null,
                        'split_depth'   => null,
                        'state_hash'    => null,
                        'tick'          => null,
                        'tock'          => null,
                        'workchain_id'  => 0,
                    ],
                ]
            )
        );

        self::assertEquals($expected, $this->net->waitForCollection($query));
    }

    /**
     * @covers ::subscribeCollection
     * @covers ::unsubscribe
     */
    public function testSubscribeCollection(): void
    {
        $minBalanceDelta = 1_000;

        $query = new ParamsOfSubscribeCollection(
            'transactions',
            [
                'id',
                'status',
                'status_name',
                'block_id',
                'account_addr',
                'lt',
                'prev_trans_hash',
                'prev_trans_lt',
                'now',
                'balance_delta',
            ],
            (new Filters())->add(
                'balance_delta',
                Filters::GT,
                '0x' . dechex($minBalanceDelta)
            )
        );

        $result = $this->net->subscribeCollection($query);
        self::assertFalse($result->isFinished());
        self::assertGreaterThan(0, $result->getHandle());

        // Event saver (for manual check event data)
        $saver = $this->eventSaver->getSaver(__METHOD__);

        $counter = 0;
        foreach ($result->getIterator() as $event) {
            // Save event data to dump file (tests/Integration/artifacts/*.txt)
            $saver->send($event->getResult());

            $eventResult = $event->getResult();

            self::assertNotEmpty($eventResult['id']);
            self::assertNotEmpty($eventResult['status']);
            self::assertNotEmpty($eventResult['status_name']);
            self::assertNotEmpty($eventResult['block_id']);
            self::assertNotEmpty($eventResult['account_addr']);
            self::assertNotEmpty($eventResult['lt']);
            self::assertNotEmpty($eventResult['prev_trans_hash']);
            self::assertNotEmpty($eventResult['prev_trans_lt']);
            self::assertNotEmpty($eventResult['now']);
            self::assertNotEmpty($eventResult['balance_delta']);
            self::assertGreaterThan($minBalanceDelta, hexdec($eventResult['balance_delta']));

            if (++$counter > 3) {
                $this->net->unsubscribe($result->getHandle());
                // or call: $result->stop();
            }
        }

        self::assertTrue($result->isFinished());
    }

    /**
     * @covers ::query
     */
    public function testQuery(): void
    {
        $query = <<<'QUERY'
            query($time: Float) {
                messages(
                    filter: {
                        created_at: {
                            ge: $time
                        }
                    }
                    limit:5
                ){id}
            }
        QUERY;

        $result = $this->net->query(
            $query,
            [
                'time' => time() - 7200
            ]
        );

        self::assertGreaterThan(0, $result->getMessages());
    }

    /**
     * @covers ::findLastShardBlock
     */
    public function testFindLastShardBlock(): void
    {
        $address = $this->dataProvider->getGiverAddress();

        $result = $this->net->findLastShardBlock($address);

        self::assertNotEmpty($result->getBlockId());
    }

    /**
     * is broken?
     * @see https://github.com/tonlabs/TON-SDK/blob/7dcd23d861add85a7ee307f19b82e423f533fea8/ton_client/src/net/tests.rs#L462-L479
     *
     * @covers ::setEndpoints
     * @covers ::fetchEndpoints
     */
    public function testSetEndpointsAndGetEndpoints(): void
    {
        self::markTestSkipped('Broken test.');

        $this->net->setEndpoints(
            [
                'cinet.tonlabs.io',
                'cinet2.tonlabs.io/'
            ]
        );

        $result = $this->net->fetchEndpoints();

        $expected = [
            'https://cinet.tonlabs.io',
            'https://cinet2.tonlabs.io',
        ];

        self::assertEquals($expected, $result->getEndpoints());
    }

    /**
     * @covers ::aggregateCollection
     */
    public function testAggregateCollection(): void
    {
        $aggregation = new Aggregation();
        $aggregation->add('id', Aggregation::COUNT);
        $aggregation->add('balance', Aggregation::AVERAGE);

        $query = new ParamsOfAggregateCollection('accounts');
        $query->setAggregation($aggregation);

        $resultOfAggregateCollection = $this->net->aggregateCollection($query);

        self::assertGreaterThan(0, $resultOfAggregateCollection->getValues()[0]);
        self::assertGreaterThan(0, $resultOfAggregateCollection->getValues()[1]);
    }

    /**
     * @covers ::batchQuery
     */
    public function testBatchQuery(): void
    {
        // Query 1
        $query1 = new ParamsOfQueryCollection('blocks_signatures', ['id']);
        $query1->setLimit(1);

        // Query 2
        $query2 = new ParamsOfWaitForCollection('transactions', ['id', 'now']);
        $filters = new Filters();
        $filters->add('now', Filters::GT, 20);
        $query2->setFilters($filters);

        // Query 3
        $aggregation = new Aggregation();
        $aggregation->add('id', Aggregation::COUNT);
        $aggregation->add('balance', Aggregation::AVERAGE);
        $query3 = new ParamsOfAggregateCollection('accounts');
        $query3->setAggregation($aggregation);

        $query = new ParamsOfBatchQuery();
        $query->add($query1);
        $query->add($query2);
        $query->add($query3);

        $resultOfBatchQuery = $this->net->batchQuery($query);

        self::assertCount(3, $resultOfBatchQuery->getResults());
    }

    /**
     * @covers ::queryCounterparties
     */
    public function testQueryCounterparties(): void
    {
        self::markTestSkipped('Broken test.');

        $account = '-1:7777777777777777777777777777777777777777777777777777777777777777';

        $expected = new ResultOfQueryCounterparties(
            new Response(
                [
                    'result' => [
                        [
                            'counterparty'    => '0:954688c088881241c5c6b248da398aefa2752bd5a97202f41454fdf3671e671c',
                            'last_message_id' => '972e6d570c2a114e09a291435c69d29333e3ad67a7e60445fff517d58d38cefd',
                            'cursor'          => '1614858473/0:954688c088881241c5c6b248da398aefa2752bd5a97202f41454fdf3671e671c',
                        ],
                        $part2 = [
                            'counterparty'    => '0:2bb4a0e8391e7ea8877f4825064924bd41ce110fce97e939d3323999e1efbb13',
                            'last_message_id' => '33ce8939b7cec3018272ecf47381782d502ca7a81e7ff9385803f69a03fced35',
                            'cursor'          => '1610344806/0:2bb4a0e8391e7ea8877f4825064924bd41ce110fce97e939d3323999e1efbb13',
                        ],
                    ]
                ]
            )
        );

        $resultOfQueryCounterparties = $this->net->queryCounterparties(
            $account,
            'counterparty last_message_id cursor',
            5
        );

        self::assertEquals($expected, $resultOfQueryCounterparties);

        $result = $resultOfQueryCounterparties->getResult();
        $last = reset($result);

        $resultOfQueryCounterparties = $this->net->queryCounterparties(
            $account,
            'counterparty last_message_id cursor',
            5,
            $last['cursor']
        );

        $expected = new ResultOfQueryCounterparties(
            new Response(
                [
                    'result' => [
                        $part2,
                    ]
                ]
            )
        );

        self::assertEquals($expected, $resultOfQueryCounterparties);
    }

    /**
     * @covers ::queryTransactionTree
     */
    public function testQueryTransactionTree(): void
    {
        $filters = new Filters();
        $filters->add('msg_type', Filters::EQ, 1);

        $query = new ParamsOfQueryCollection(
            'messages',
            [],
            $filters
        );

        $query->addResultField(
            <<<FIELDS
                id dst dst_transaction {
                    id aborted out_messages {
                        id dst msg_type_name dst_transaction {
                            id aborted out_messages {
                                id dst msg_type_name dst_transaction {
                                    id aborted
                                }
                            }
                        }
                    }
                }
            FIELDS
        );

        $resultOfQueryCollection = $this->net->queryCollection($query);

        $abiRegistry = [
            AbiType::fromArray($this->dataProvider->getHelloAbiArray())
        ];

        foreach ($resultOfQueryCollection->getResult() as $message) {
            $messageId = $message['id'] ?? null;
            if ($messageId === null) {
                continue;
            }

            $resultOfQueryTransactionTree = $this->net->queryTransactionTree(
                $messageId,
                $abiRegistry
            );

            self::assertContainsOnlyInstancesOf(
                MessageNode::class,
                $resultOfQueryTransactionTree->getMessages()
            );

            self::assertContainsOnlyInstancesOf(
                TransactionNode::class,
                $resultOfQueryTransactionTree->getTransactions()
            );
        }
    }
}
