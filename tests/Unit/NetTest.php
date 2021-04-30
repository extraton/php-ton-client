<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Net\Aggregation;
use Extraton\TonClient\Entity\Net\EndpointsSet;
use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\ParamsOfBatchQuery;
use Extraton\TonClient\Entity\Net\QueryInterface;
use Extraton\TonClient\Entity\Net\ResultOfAggregateCollection;
use Extraton\TonClient\Entity\Net\ResultOfBatchQuery;
use Extraton\TonClient\Entity\Net\ResultOfFindLastShardBlock;
use Extraton\TonClient\Entity\Net\ResultOfQuery;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCounterparties;
use Extraton\TonClient\Entity\Net\ResultOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ResultOfWaitForCollection;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Net;
use PHPUnit\Framework\MockObject\MockObject;

use function microtime;
use function time;
use function uniqid;

/**
 * Unit tests for Net module
 *
 * @coversDefaultClass \Extraton\TonClient\Net
 */
class NetTest extends AbstractModuleTest
{
    private Net $net;

    /** @var MockObject|QueryInterface */
    private MockObject $mockQuery;

    public function setUp(): void
    {
        parent::setUp();
        $this->net = new Net($this->mockTonClient);

        $this->mockQuery = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'getCollection',
                    'getResult',
                    'getFilters',
                    'getOrderBy',
                    'getLimit',
                    'getTimeout',
                    'getAggregation',
                    'jsonSerialize',
                ]
            )
            ->getMock();
    }

    /**
     * @covers ::queryCollection
     */
    public function testQueryCollection(): void
    {
        $collection = uniqid(microtime(), true);
        $result = uniqid(microtime(), true);
        $filters = new Filters();
        $orderBy = new OrderBy();
        $limit = time();

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockQuery->expects(self::once())
            ->method('getCollection')
            ->with()
            ->willReturn($collection);

        $this->mockQuery->expects(self::once())
            ->method('getResult')
            ->with()
            ->willReturn($result);

        $this->mockQuery->expects(self::once())
            ->method('getFilters')
            ->with()
            ->willReturn($filters);

        $this->mockQuery->expects(self::once())
            ->method('getOrderBy')
            ->with()
            ->willReturn($orderBy);

        $this->mockQuery->expects(self::once())
            ->method('getLimit')
            ->with()
            ->willReturn($limit);

        $this->mockQuery->expects(self::never())
            ->method('getTimeout');

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'net.query_collection',
                [
                    'collection' => $collection,
                    'result'     => $result,
                    'filter'     => $filters,
                    'order'      => $orderBy,
                    'limit'      => $limit,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfQueryCollection($response);

        self::assertEquals($expected, $this->net->queryCollection($this->mockQuery));
    }

    /**
     * @covers ::waitForCollection
     */
    public function testWaitForCollection(): void
    {
        $collection = uniqid(microtime(), true);
        $result = uniqid(microtime(), true);
        $filters = new Filters();
        $timeout = time();

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockQuery->expects(self::once())
            ->method('getCollection')
            ->with()
            ->willReturn($collection);

        $this->mockQuery->expects(self::once())
            ->method('getResult')
            ->with()
            ->willReturn($result);

        $this->mockQuery->expects(self::once())
            ->method('getFilters')
            ->with()
            ->willReturn($filters);

        $this->mockQuery->expects(self::never())
            ->method('getOrderBy');

        $this->mockQuery->expects(self::never())
            ->method('getLimit');

        $this->mockQuery->expects(self::once())
            ->method('getTimeout')
            ->with()
            ->willReturn($timeout);

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'net.wait_for_collection',
                [
                    'collection' => $collection,
                    'result'     => $result,
                    'filter'     => $filters,
                    'timeout'    => $timeout,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfWaitForCollection($response);

        self::assertEquals($expected, $this->net->waitForCollection($this->mockQuery));
    }

    /**
     * @covers ::subscribeCollection
     */
    public function testSubscribeCollection(): void
    {
        $collection = uniqid(microtime(), true);
        $result = uniqid(microtime(), true);
        $filters = new Filters();

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockQuery->expects(self::once())
            ->method('getCollection')
            ->with()
            ->willReturn($collection);

        $this->mockQuery->expects(self::once())
            ->method('getResult')
            ->with()
            ->willReturn($result);

        $this->mockQuery->expects(self::once())
            ->method('getFilters')
            ->with()
            ->willReturn($filters);

        $this->mockQuery->expects(self::never())
            ->method('getOrderBy');

        $this->mockQuery->expects(self::never())
            ->method('getLimit');

        $this->mockQuery->expects(self::never())
            ->method('getTimeout');

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'net.subscribe_collection',
                [
                    'collection' => $collection,
                    'result'     => $result,
                    'filter'     => $filters,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSubscribeCollection($response, $this->net);

        self::assertEquals($expected, $this->net->subscribeCollection($this->mockQuery));
    }

    /**
     * @covers ::unsubscribe
     */
    public function testUnsubscribe(): void
    {
        $handle = time();

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn([]);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'net.unsubscribe',
                [
                    'handle' => $handle,
                ]
            )
            ->willReturn($this->mockPromise);

        $this->net->unsubscribe($handle);
    }

    /**
     * @covers ::query
     */
    public function testQuery(): void
    {
        $query = uniqid(microtime(), true);
        $variables = [
            uniqid(microtime(), true) => uniqid(microtime(), true)
        ];

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
                'net.query',
                [
                    'query'     => $query,
                    'variables' => $variables,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfQuery($response);

        self::assertEquals($expected, $this->net->query($query, $variables));
    }

    /**
     * @covers ::findLastShardBlock
     */
    public function testFindLastShardBlock(): void
    {
        $address = uniqid(microtime(), true);

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
                'net.find_last_shard_block',
                [
                    'address' => $address,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfFindLastShardBlock($response);

        self::assertEquals($expected, $this->net->findLastShardBlock($address));
    }

    /**
     * @covers ::setEndpoints
     */
    public function testSetEndpoints(): void
    {
        $endpoints = [
            uniqid(microtime(), true) => uniqid(microtime(), true),
        ];

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
                'net.set_endpoints',
                [
                    'endpoints' => $endpoints,
                ]
            )
            ->willReturn($this->mockPromise);

        $this->net->setEndpoints($endpoints);
    }

    /**
     * @covers ::fetchEndpoints
     */
    public function testFetchEndpoints(): void
    {
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
                'net.fetch_endpoints'
            )
            ->willReturn($this->mockPromise);

        $expected = new EndpointsSet($response);

        self::assertEquals($expected, $this->net->fetchEndpoints());
    }

    /**
     * @covers ::aggregateCollection
     */
    public function testAggregateCollection(): void
    {
        $collection = uniqid(microtime(), true);
        $filters = new Filters();
        $aggregation = new Aggregation();

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockQuery->expects(self::once())
            ->method('getCollection')
            ->with()
            ->willReturn($collection);

        $this->mockQuery->expects(self::once())
            ->method('getFilters')
            ->with()
            ->willReturn($filters);

        $this->mockQuery->expects(self::once())
            ->method('getAggregation')
            ->with()
            ->willReturn($aggregation);

        $this->mockQuery->expects(self::never())
            ->method('getResult');

        $this->mockQuery->expects(self::never())
            ->method('getOrderBy');

        $this->mockQuery->expects(self::never())
            ->method('getLimit');

        $this->mockQuery->expects(self::never())
            ->method('getTimeout');

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'net.aggregate_collection',
                [
                    'collection' => $collection,
                    'filter'     => $filters,
                    'fields'     => $aggregation,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfAggregateCollection($response);

        self::assertEquals($expected, $this->net->aggregateCollection($this->mockQuery));
    }

    /**
     * @covers ::batchQuery
     */
    public function testBatchQuery(): void
    {
        $query = new ParamsOfBatchQuery();

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
                'net.batch_query',
                [
                    'operations' => $query,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfBatchQuery($response);

        self::assertEquals($expected, $this->net->batchQuery($query));
    }

    /**
     * @covers ::queryCounterparties
     */
    public function testQueryCounterparties(): void
    {
        $account = uniqid(microtime(), true);
        $result = uniqid(microtime(), true);
        $first = time();
        $after = uniqid(microtime(), true);

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
                'net.query_counterparties',
                [
                    'account' => $account,
                    'result'  => $result,
                    'first'   => $first,
                    'after'   => $after,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfQueryCounterparties($response);

        self::assertEquals(
            $expected,
            $this->net->queryCounterparties($account, $result, $first, $after)
        );
    }
}
