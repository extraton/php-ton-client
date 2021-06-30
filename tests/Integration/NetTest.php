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
                    1624193979,
                    1624193981,
                    1624194030,
                    1624194042,
                    1624194046,
                    1624194047,
                    1624194082,
                    1624194087,
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
                            'boc'           => 'te6ccgEBAQEANQAAZcAIFgllkdKjS6n9ZylXSdQ75EfFV2x/sOb732nMcmuIrkICU8MGeeE4AAAAAgzCYQhZBA==',
                            'code'          => null,
                            'code_hash'     => null,
                            'data'          => null,
                            'data_hash'     => null,
                            'due_payment'   => null,
                            'id'            => '0:816096591d2a34ba9fd67295749d43be447c5576c7fb0e6fbdf69cc726b88ae4',
                            'last_paid'     => 1624194087,
                            'last_trans_lt' => '0x83309842',
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
                            'balance'       => '0x0',
                            'boc'           => 'te6ccgEBBwEAtwACZ8AKAd9SCMw7kONJDRwRdD1Rly+YiVYiWXLn6m5jxB5T0lIOgOsDBnnhEAAAAAIMC0YME0AGAQGRgAAAvRTBcbpAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAd7RL+x2XgrWl0auki0JhOKSsM7UMXzFemn6mjI5pNEoAICA88gBQMBAd4EAAPQIABB2O9ol/Y7LwVrS6NXSRaEwnFJWGdqGL5ivTT9TRkc0miUAAA=',
                            'code'          => 'te6ccgEBAQEAAgAAAA==',
                            'code_hash'     => '96a296d224f285c67bee93c30f8a309157f0daa35dc5b87e410b78630a09cfc7',
                            'data'          => 'te6ccgEBBQEAfQABkYAAAL0UwXG6QAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHe0S/sdl4K1pdGrpItCYTikrDO1DF8xXpp+poyOaTRKABAgPPIAQCAQHeAwAD0CAAQdjvaJf2Oy8Fa0ujV0kWhMJxSVhnahi+Yr00/U0ZHNJolA==',
                            'data_hash'     => 'c891c6d80e1fb3819edf6498d85532e5d2a06132d65e39c6cbe5696cce741ca0',
                            'due_payment'   => null,
                            'id'            => '0:a01df5208cc3b90e3490d1c11743d51972f988956225972e7ea6e63c41e53d25',
                            'last_paid'     => 1624194082,
                            'last_trans_lt' => '0x8302d183',
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
                Filters::IN,
                [
                    1624193979,
                    1624193981,
                    1624194030,
                    1624194042,
                    1624194046,
                    1624194047,
                    1624194082,
                    1624194087,
                ]
            ),
            30_000
        );

        $expected = new ResultOfWaitForCollection(
            new Response(
                [
                    'result' => [
                        'acc_type'      => 1,
                        'acc_type_name' => 'Active',
                        'balance'       => '0x126b1120f',
                        'boc'           => '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',
                        'code'          => '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',
                        'code_hash'     => '207dc560c5956de1a2c1479356f8f3ee70a59767db2bf4788b1d61ad42cdad82',
                        'data'          => 'te6ccgEBAgEAmAAB31KrRcGQJ6s7uOu8suIaQz06z4wdGM1IvOImY35xYyfZAAABeimBUBepVaLgyBPVndx13llxDSGenWfGDoxmpF5xEzG/OLGT7IAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAICAAAAAAEBgBAEWgClVouDIE9Wd3HXeWXENIZ6dZ8YOjGakXnETMb84sZPsgEA==',
                        'data_hash'     => 'cb2d6b16b9f66b210aa4f6b673318c7d5b784c05908ef2f8d33a921370ce28ab',
                        'due_payment'   => null,
                        'id'            => '0:a9415511eb398e180eccf18810b145fe49f131e412d280a26cff92e90b9fb853',
                        'last_paid'     => 1624193979,
                        'last_trans_lt' => '0x80266582',
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
