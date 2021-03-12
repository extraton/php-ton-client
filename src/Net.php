<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Net\EndpointsSet;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Net\QueryInterface;
use Extraton\TonClient\Entity\Net\ResultOfFindLastShardBlock;
use Extraton\TonClient\Entity\Net\ResultOfQuery;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ResultOfWaitForCollection;
use Extraton\TonClient\Exception\TonException;

/**
 * Net module
 */
class Net extends AbstractModule
{
    /**
     * Queries collection data
     *
     * @param QueryInterface|ParamsOfQueryCollection $query
     * @return ResultOfQueryCollection
     * @throws TonException
     */
    public function queryCollection(QueryInterface $query): ResultOfQueryCollection
    {
        return new ResultOfQueryCollection(
            $this->tonClient->request(
                'net.query_collection',
                [
                    'collection' => $query->getCollection(),
                    'result'     => $query->getResult(),
                    'filter'     => $query->getFilters(),
                    'order'      => $query->getOrderBy(),
                    'limit'      => $query->getLimit(),
                ]
            )->wait()
        );
    }

    /**
     * Returns an object that fulfills the conditions or waits for its appearance
     *
     * @param QueryInterface|ParamsOfWaitForCollection $query
     * @return ResultOfWaitForCollection
     * @throws TonException
     */
    public function waitForCollection(QueryInterface $query): ResultOfWaitForCollection
    {
        return new ResultOfWaitForCollection(
            $this->tonClient->request(
                'net.wait_for_collection',
                [
                    'collection' => $query->getCollection(),
                    'result'     => $query->getResult(),
                    'filter'     => $query->getFilters(),
                    'timeout'    => $query->getTimeout(),
                ]
            )->wait()
        );
    }

    /**
     * Creates a subscription
     *
     * @param QueryInterface|ParamsOfSubscribeCollection $query
     * @return ResultOfSubscribeCollection
     * @throws TonException
     */
    public function subscribeCollection(QueryInterface $query): ResultOfSubscribeCollection
    {
        return new ResultOfSubscribeCollection(
            $this->tonClient->request(
                'net.subscribe_collection',
                [
                    'collection' => $query->getCollection(),
                    'result'     => $query->getResult(),
                    'filter'     => $query->getFilters(),
                ]
            )->wait(),
            $this
        );
    }

    /**
     * Cancels a subscription
     *
     * @param int $handle
     * @throws TonException
     */
    public function unsubscribe(int $handle): void
    {
        $this->tonClient->request(
            'net.unsubscribe',
            [
                'handle' => $handle,
            ]
        )->wait();
    }

    /**
     * Performs DAppServer GraphQL query
     *
     * @param string $query GraphQL query text
     * @param array<mixed>|null $variables Variables used in query. Must be a map with named values that can be used in query.
     * @return ResultOfQuery
     * @throws TonException
     */
    public function query(string $query, ?array $variables = null): ResultOfQuery
    {
        return new ResultOfQuery(
            $this->tonClient->request(
                'net.query',
                [
                    'query'     => $query,
                    'variables' => $variables,
                ]
            )->wait()
        );
    }

    /**
     * Returns ID of the last block in a specified account shard
     *
     * @param string $address Account address
     * @return ResultOfFindLastShardBlock
     * @throws TonException
     */
    public function findLastShardBlock(string $address): ResultOfFindLastShardBlock
    {
        return new ResultOfFindLastShardBlock(
            $this->tonClient->request(
                'net.find_last_shard_block',
                [
                    'address' => $address,
                ]
            )->wait()
        );
    }

    /**
     * Sets the list of endpoints to use on reinit
     *
     * @param array<string> $endpoints List of endpoints provided by server
     * @throws TonException
     */
    public function setEndpoints(array $endpoints): void
    {
        $this->tonClient->request(
            'net.set_endpoints',
            [
                'endpoints' => $endpoints,
            ]
        )->wait();
    }

    /**
     * Requests the list of alternative endpoints from server
     *
     * @return EndpointsSet
     * @throws TonException
     */
    public function fetchEndpoints(): EndpointsSet
    {
        return new EndpointsSet(
            $this->tonClient->request(
                'net.fetch_endpoints'
            )->wait()
        );
    }

    /**
     * Suspends network module to stop any network activity
     *
     * @throws TonException
     */
    public function suspend(): void
    {
        $this->tonClient->request(
            'net.suspend'
        )->wait();
    }

    /**
     * Resumes network module to enable network activity
     *
     * @throws TonException
     */
    public function resume(): void
    {
        $this->tonClient->request(
            'net.resume'
        )->wait();
    }
}
