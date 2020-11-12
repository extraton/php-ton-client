<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Entity\Net\OrderBy;
use Extraton\TonClient\Entity\Net\QueryInterface;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
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
                ]
            )
            ->getMock();
    }

    /**
     * @covers ::queryCollection
     */
    public function testQueryCollectionWithSuccessResult(): void
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
                    'orderBy'    => $orderBy,
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
    public function testWaitForCollectionWithSuccessResult(): void
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
    public function testSubscribeCollectionWithSuccessResult(): void
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
    public function testUnsubscribeWithSuccessResult(): void
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
}
