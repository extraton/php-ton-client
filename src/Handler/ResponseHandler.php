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

    /** @var Response[] */
    private array $responses = [];

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

        if ($responseType === ResponseType::SUCCESS) {
            $this->handleSuccess($requestId, $result, $finished);
        } elseif ($responseType === ResponseType::ERROR) {
            $this->handleError($requestId, $result);
        } elseif ($responseType >= ResponseType::CUSTOM) {
            $this->handleData($requestId, $result, $finished);
        } elseif ($responseType === ResponseType::NOP) {
            $this->handleNop($requestId);
        } else {
            throw new LogicException('Unknown response data.');
        }
    }

    /**
     * @param int $requestId
     * @param array<mixed> $result
     * @param bool $finished
     */
    public function handleSuccess(int $requestId, array $result, bool $finished): void
    {
        $promise = $this->getPromise($requestId);
        $this->unregisterPromise($requestId);

        $response = new Response($result, $finished);
        if (!$finished) {
            $this->addResponse($requestId, $response);
        }

        $promise->resolve($response);
    }

    public function getPromise(int $requestId): Promise
    {
        if (!isset($this->promises[$requestId])) {
            throw new LogicException(sprintf('Promise not found by id "%s".', $requestId));
        }

        return $this->promises[$requestId];
    }

    public function unregisterPromise(int $requestId): void
    {
        if (!isset($this->promises[$requestId])) {
            throw new LogicException(sprintf('Promise not found by id "%s".', $requestId));
        }

        unset($this->promises[$requestId]);
    }

    public function addResponse(int $requestId, Response $response): void
    {
        $this->responses[$requestId] = $response;
    }

    /**
     * @param int $requestId
     * @param array<mixed> $result
     */
    public function handleError(int $requestId, array $result): void
    {
        $promise = $this->getPromise($requestId);
        $this->unregisterPromise($requestId);

        $this->removeResponse($requestId);

        $promise->reject(RequestException::create($result));
    }

    public function removeResponse(int $requestId): void
    {
        if (isset($this->responses[$requestId])) {
            unset($this->responses[$requestId]);
        }
    }

    /**
     * @param int $requestId
     * @param array<mixed> $result
     * @param bool $finished
     */
    public function handleData(int $requestId, array $result, bool $finished): void
    {
        $response = $this->getResponse($requestId);

        if ($finished) {
            $this->removeResponse($requestId);
        }

        $response($result);
    }

    public function getResponse(int $requestId): Response
    {
        if (!isset($this->responses[$requestId])) {
            throw new RuntimeException('Response not found.');
        }

        return $this->responses[$requestId];
    }

    public function handleNop(int $requestId): void
    {
        $response = $this->getResponse($requestId);
        $response->finish();

        $this->removeResponse($requestId);
    }
}
