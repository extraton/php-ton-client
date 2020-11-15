<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Binding\Type\ResponseType;
use Extraton\TonClient\Exception\SDKException;
use FFI\CData;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;
use JsonException;
use LogicException;

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

    public function getPromise(int $requestId): ?Promise
    {
        return $this->promises[$requestId] ?? null;
    }

    public function unregisterPromise(int $requestId): void
    {
        if (isset($this->promises[$requestId])) {
            unset($this->promises[$requestId]);
        }
    }

    public function addResponse(int $requestId, Response $response): void
    {
        $this->responses[$requestId] = $response;
    }

    public function getResponse(int $requestId): ?Response
    {
        return $this->responses[$requestId] ?? null;
    }

    public function removeResponse(int $requestId): void
    {
        if (isset($this->responses[$requestId])) {
            unset($this->responses[$requestId]);
        }
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
            $this->handleNop($requestId, $finished);
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
        $response = $this->getResponse($requestId);
        if ($response === null) {
            $response = new Response($result, true, $finished);

            $this->addResponse($requestId, $response);
        } else {
            $response->setResponseData($result);
        }

        if (!$finished) {
            return;
        }

        $response->finish();

        $promise = $this->getPromise($requestId);
        if (($promise !== null) && Is::pending($promise)) {
            $promise->resolve($response);
        }

        $this->unregisterPromise($requestId);
        $this->removeResponse($requestId);
    }

    /**
     * @param int $requestId
     * @param array<mixed> $result
     */
    public function handleError(int $requestId, array $result): void
    {
        $promise = $this->getPromise($requestId);
        if ($promise !== null) {
            $promise->reject(SDKException::create($result));
        }

        $this->unregisterPromise($requestId);
        $this->removeResponse($requestId);
    }

    /**
     * @param int $requestId
     * @param array<mixed> $result
     * @param bool $finished
     */
    public function handleData(int $requestId, array $result, bool $finished): void
    {
        $response = $this->getResponse($requestId);
        if ($response === null) {
            $response = new Response([], false, false);

            $this->addResponse($requestId, $response);
        }

        $response($result);

        if ($finished) {
            $response->finish();
        }

        $promise = $this->getPromise($requestId);
        if (($promise !== null) && Is::pending($promise)) {
            $promise->resolve($response);
        }
    }

    /**
     * @param int $requestId
     * @param bool $finished
     */
    public function handleNop(int $requestId, bool $finished): void
    {
        if (!$finished) {
            return;
        }

        $response = $this->getResponse($requestId);
        if ($response === null) {
            return;
        }

        $response->finish();

        $promise = $this->getPromise($requestId);
        if (($promise !== null) && Is::pending($promise)) {
            $promise->resolve($response);
        }

        $this->unregisterPromise($requestId);
        $this->removeResponse($requestId);
    }
}
