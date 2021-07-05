<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Net\EndpointsSet;
use Extraton\TonClient\Entity\Net\ParamsOfAggregateCollection;
use Extraton\TonClient\Entity\Net\ParamsOfBatchQuery;
use Extraton\TonClient\Entity\Net\ParamsOfQueryCollection;
use Extraton\TonClient\Entity\Net\ParamsOfSubscribeCollection;
use Extraton\TonClient\Entity\Net\ParamsOfWaitForCollection;
use Extraton\TonClient\Entity\Net\QueryInterface;
use Extraton\TonClient\Entity\Net\RegisteredIterator;
use Extraton\TonClient\Entity\Net\ResultOfAggregateCollection;
use Extraton\TonClient\Entity\Net\ResultOfBatchQuery;
use Extraton\TonClient\Entity\Net\ResultOfFindLastShardBlock;
use Extraton\TonClient\Entity\Net\ResultOfIteratorNext;
use Extraton\TonClient\Entity\Net\ResultOfQuery;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;
use Extraton\TonClient\Entity\Net\ResultOfQueryCounterparties;
use Extraton\TonClient\Entity\Net\ResultOfQueryTransactionTree;
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

    /**
     * Aggregates collection data.
     * Aggregates values from the specified fields for records that satisfies the filter conditions
     *
     * @param QueryInterface|ParamsOfAggregateCollection $query
     * @return ResultOfAggregateCollection
     * @throws TonException
     */
    public function aggregateCollection(QueryInterface $query): ResultOfAggregateCollection
    {
        return new ResultOfAggregateCollection(
            $this->tonClient->request(
                'net.aggregate_collection',
                [
                    'collection' => $query->getCollection(),
                    'filter'     => $query->getFilters(),
                    'fields'     => $query->getAggregation(),
                ]
            )->wait()
        );
    }

    /**
     * Performs multiple queries per single fetch
     *
     * @param ParamsOfBatchQuery $query List of query operations that must be performed per single fetch
     * @return ResultOfBatchQuery
     * @throws TonException
     */
    public function batchQuery(ParamsOfBatchQuery $query): ResultOfBatchQuery
    {
        return new ResultOfBatchQuery(
            $this->tonClient->request(
                'net.batch_query',
                [
                    'operations' => $query,
                ]
            )->wait()
        );
    }

    /**
     * Allows to query and paginate through the list of accounts that the specified account has interacted with,
     * sorted by the time of the last internal message between accounts
     *
     * @param string $account Account address
     * @param string $result Projection (result) string
     * @param int|null $first Number of counterparties to return
     * @param string|null $after cursor field of the last received result
     * @return ResultOfQueryCounterparties
     * @throws TonException
     */
    public function queryCounterparties(
        string $account,
        string $result,
        ?int $first = null,
        ?string $after = null
    ): ResultOfQueryCounterparties {
        return new ResultOfQueryCounterparties(
            $this->tonClient->request(
                'net.query_counterparties',
                [
                    'account' => $account,
                    'result'  => $result,
                    'first'   => $first,
                    'after'   => $after,
                ]
            )->wait()
        );
    }

    /**
     * Returns transactions tree for specific message.
     * Performs recursive retrieval of the transactions tree produced by the specific message:
     * in_msg -> dst_transaction -> out_messages -> dst_transaction -> ...
     * All retrieved messages and transactions will be included
     * into result.messages and result.transactions respectively.
     *
     * @param string $inMsg
     * @param AbiType[]|null $abiRegistry
     * @return ResultOfQueryTransactionTree
     * @throws TonException
     */
    public function queryTransactionTree(string $inMsg, ?array $abiRegistry = null): ResultOfQueryTransactionTree
    {
        return new ResultOfQueryTransactionTree(
            $this->tonClient->request(
                'net.query_transaction_tree',
                [
                    'in_msg'       => $inMsg,
                    'abi_registry' => $abiRegistry,
                ]
            )->wait()
        );
    }

    /**
     * Creates block iterator.
     * Block iterator uses robust iteration methods that guaranties that every block
     * in the specified range isn't missed or iterated twice.
     *
     * @param int|null $startTime Starting time to iterate from.
     * @param int|null $endTime Optional end time to iterate for.
     * @param list<string>|null $shardFilter Shard prefix filters.
     * @param string|null $result Account address filter.
     * @return RegisteredIterator
     * @throws TonException
     */
    public function createBlockIterator(
        ?int $startTime,
        ?int $endTime,
        ?array $shardFilter,
        ?string $result
    ): RegisteredIterator {
        return new RegisteredIterator(
            $this->tonClient->request(
                'net.create_block_iterator',
                [
                    'start_time'   => $startTime,
                    'end_time'     => $endTime,
                    'shard_filter' => $shardFilter,
                    'result'       => $result,
                ]
            )->wait()
        );
    }

    /**
     * Resumes block iterator.
     * The iterator stays exactly at the same position where the resume_state was cached.
     * Application should call the remove_iterator when iterator is no longer required.
     *
     * @param mixed $resumeState Iterator state from which to resume. Same as value returned from iterator_next.
     * @return RegisteredIterator
     * @throws TonException
     */
    public function resumeBlockIterator($resumeState): RegisteredIterator
    {
        return new RegisteredIterator(
            $this->tonClient->request(
                'net.resume_block_iterator',
                [
                    'resume_state' => $resumeState,
                ]
            )->wait()
        );
    }

    /**
     * Creates transaction iterator.
     * Transaction iterator uses robust iteration methods that guaranty that every transaction
     * in the specified range isn't missed or iterated twice.
     *
     * @param int|null $startTime Starting time to iterate from.
     * @param int|null $endTime Optional end time to iterate for.
     * @param list<string>|null $shardFilter Shard prefix filters.
     * @param list<string>|null $accountsFilter Account address filter.
     * @param string|null $result Projection (result) string.
     * @param bool|null $includeTransfers Include transfers field in iterated transactions.
     * @return RegisteredIterator
     * @throws TonException
     */
    public function createTransactionIterator(
        ?int $startTime,
        ?int $endTime,
        ?array $shardFilter,
        ?array $accountsFilter,
        ?string $result,
        ?bool $includeTransfers
    ): RegisteredIterator {
        return new RegisteredIterator(
            $this->tonClient->request(
                'net.create_transaction_iterator',
                [
                    'start_time'        => $startTime,
                    'end_time'          => $endTime,
                    'shard_filter'      => $shardFilter,
                    'accounts_filter'   => $accountsFilter,
                    'result'            => $result,
                    'include_transfers' => $includeTransfers,
                ]
            )->wait()
        );
    }

    /**
     * Resumes transaction iterator.
     * The iterator stays exactly at the same position where the resume_state was caught.
     * Note that resume_state doesn't store the account filter.
     * If the application requires to use the same account filter as it was when the iterator was created
     * then the application must pass the account filter again in accounts_filter parameter.
     *
     * @param mixed $resumeState Iterator state from which to resume. Same as value returned from iterator_next.
     * @param list<string>|null $accountsFilter Account address filter. Application can specify the list of accounts for which it wants to iterate transactions.
     * @return RegisteredIterator
     * @throws TonException
     */
    public function resumeTransactionIterator(
        $resumeState,
        ?array $accountsFilter
    ): RegisteredIterator {
        return new RegisteredIterator(
            $this->tonClient->request(
                'net.resume_transaction_iterator',
                [
                    'resume_state'    => $resumeState,
                    'accounts_filter' => $accountsFilter,
                ]
            )->wait()
        );
    }

    /**
     * Returns next available items.
     * In addition to available items this function returns the has_more flag indicating
     * that the iterator isn't reach the end of the iterated range yet.
     *
     * @param int $iterator Iterator handle
     * @param int|null $limit Maximum count of the returned items. If value is missing or is less than 1 the library uses 1.
     * @param bool|null $returnResumeState Indicates that function must return the iterator state that can be used for resuming iteration.
     * @return ResultOfIteratorNext
     * @throws TonException
     */
    public function iteratorNext(
        int $iterator,
        ?int $limit,
        ?bool $returnResumeState
    ): ResultOfIteratorNext {
        return new ResultOfIteratorNext(
            $this->tonClient->request(
                'net.iterator_next',
                [
                    'iterator'            => $iterator,
                    'limit'               => $limit,
                    'return_resume_state' => $returnResumeState,
                ]
            )->wait()
        );
    }

    /**
     * Removes an iterator
     * Frees all resources allocated in library to serve iterator.
     * Application always should call the remove_iterator when iterator is no longer required.
     *
     * @param int $handle Iterator handle
     * @throws TonException
     */
    public function removeIterator(int $handle): void
    {
        $this->tonClient->request(
            'net.remove_iterator',
            [
                'handle' => $handle,
            ]
        )->wait();
    }
}
