<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use GuzzleHttp\Promise\Promise;
use LogicException;

use function sprintf;

class RequestWatcher
{
    /** @var Promise[] */
    private array $promises = [];

    public function generateRequestId(): int
    {
        static $requestId = 0;

        return ++$requestId;
    }

    /**
     * @param int $requestId
     * @param Promise $promise
     */
    public function addPromise(int $requestId, Promise $promise): void
    {
        $this->promises[$requestId] = $promise;
    }

    /**
     * @param int $requestId
     * @return Promise
     */
    public function getPromise(int $requestId): Promise
    {
        if (!isset($this->promises[$requestId])) {
            throw new LogicException(sprintf('Promise not found by id "%s".', $requestId));
        }

        return $this->promises[$requestId];
    }

    /**
     * @param int $requestId
     */
    public function removePromise(int $requestId): void
    {
        if (!isset($this->promises[$requestId])) {
            throw new LogicException(sprintf('Promise not found by id "%s".', $requestId));
        }

        unset($this->promises[$requestId]);
    }

    public function count(): int
    {
        return count($this->promises);
    }
}
