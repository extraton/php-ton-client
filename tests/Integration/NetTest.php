<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfWaitForCollection;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Net;

use function dechex;
use function hexdec;

/**
 * Integration tests for Net module
 *
 * @coversDefaultClass \Extraton\TonClient\Net
 */
class NetTest extends AbstractModuleTest
{
    private Net $net;

    public function setUp(): void
    {
        parent::setUp();
        $this->net = $this->tonClient->getNet();
    }

    /**
     * @covers ::queryCollection
     */
    public function testQueryCollectionWithSuccessResult(): void
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
                    1601332024,
                    1601331924,
                    1601332491,
                    1601332679
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
                            'acc_type'      => 0,
                            'acc_type_name' => 'Uninit',
                            'balance'       => '0x64',
                            'boc'           => 'te6ccgEBAQEANQAAZcANPONfsqA+mIiQu+GohDoLSC55OOuYpSuTnkb3LKwuinICU8L7kxagAAAAANkzBwhZBA==',
                            'code'          => null,
                            'code_hash'     => null,
                            'data'          => null,
                            'data_hash'     => null,
                            'due_payment'   => null,
                            'id'            => '0:d3ce35fb2a03e988890bbe1a8843a0b482e7938eb98a52b939e46f72cac2e8a7',
                            'last_paid'     => 1601331924,
                            'last_trans_lt' => '0x364cc1c2',
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
                            'acc_type'      => 0,
                            'acc_type_name' => 'Uninit',
                            'balance'       => '0x3b9aca63',
                            'boc'           => 'te6ccgEBAQEAOAAAa8AGFOdgZ/pFnoeh90I/2nBlo1lQOzqbZKLaPxjQKSZ2sKICWcL7kxnAAAAAAOK8bwkO5rKYxA==',
                            'code'          => null,
                            'code_hash'     => null,
                            'data'          => null,
                            'data_hash'     => null,
                            'due_payment'   => null,
                            'id'            => '0:614e76067fa459e87a1f7423fda7065a359503b3a9b64a2da3f18d0292676b0a',
                            'last_paid'     => 1601332024,
                            'last_trans_lt' => '0x38af1bc2',
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
    public function testWaitForCollectionSuccessResult(): void
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
                Filters::IN,
                [
                    1601332024,
                    1601331924,
                    1601332491,
                    1601332679
                ]
            ),
            30_000
        );

        $expected = new ResultOfWaitForCollection(
            new Response(
                [
                    'result' => [
                        'acc_type'      => 0,
                        'acc_type_name' => 'Uninit',
                        'balance'       => '0x64',
                        'boc'           => 'te6ccgEBAQEANQAAZcANPONfsqA+mIiQu+GohDoLSC55OOuYpSuTnkb3LKwuinICU8L7kxagAAAAANkzBwhZBA==',
                        'code'          => null,
                        'code_hash'     => null,
                        'data'          => null,
                        'data_hash'     => null,
                        'due_payment'   => null,
                        'id'            => '0:d3ce35fb2a03e988890bbe1a8843a0b482e7938eb98a52b939e46f72cac2e8a7',
                        'last_paid'     => 1601331924,
                        'last_trans_lt' => '0x364cc1c2',
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
    public function testSubscribeCollectionSuccessResult(): void
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

        $counter = 0;
        foreach ($result as $event) {
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
            }
        }

        self::assertTrue($result->isFinished());
    }
}
