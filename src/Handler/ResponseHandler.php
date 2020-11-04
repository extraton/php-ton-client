<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Binding\Type\ResponseType;
use Extraton\TonClient\Exception\RequestException;
use FFI\CData;
use GuzzleHttp\Promise\Promise;
use JsonException;
use LogicException;
use RuntimeException;

use function sprintf;

class ResponseHandler
{
    /** @var Promise[] */
    private array $promises = [];

    private Binding $binding;

    private int $requestId;

    /**
     * @param Binding $binding
     */
    public function __construct(Binding $binding)
    {
        $this->binding = $binding;
        $this->requestId = 0;
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


    public function registerPromise(Promise $promise): int
    {
        $this->promises[++$this->requestId] = $promise;

        return $this->requestId;
    }

    /**
     * @param int $requestId
     * @param CData $paramsJson
     * @param int $responseType
     * @param bool $finished
     * @throws JsonException
     */
    public function __invoke(int $requestId, CData $paramsJson, int $responseType, bool $finished): void
    {
        $result = $this->binding->getEncoder()->decodeToArray($paramsJson);
        $promise = $this->getPromise($requestId);

        if ($finished) {
            $this->removePromise($requestId);
        }

        if ($responseType === ResponseType::SUCCESS) {
            $promise->resolve($result);
        } elseif ($responseType === ResponseType::ERROR) {
            $promise->reject(RequestException::create($result));
        } else {
            $promise->reject(new RuntimeException('Unknown error.'));
        }
    }
}
