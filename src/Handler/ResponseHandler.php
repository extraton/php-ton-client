<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Extraton\TonClient\App\AppInterface;
use Extraton\TonClient\Binding\Type\ResponseType;
use Extraton\TonClient\Exception\DataException;
use Extraton\TonClient\Exception\LogicException;
use Extraton\TonClient\Exception\SDKException;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\TonClient;
use FFI\CData;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;

use function sprintf;

/**
 * Response handler
 */
class ResponseHandler
{
    /** @var Promise[] */
    private array $promises = [];

    /** @var Response[] */
    private array $responses = [];

    private int $requestId;

    private TonClient $tonClient;

    private ?AppInterface $app;

    /**
     * @param TonClient $tonClient
     * @param AppInterface|null $app
     */
    public function __construct(TonClient $tonClient, AppInterface $app = null)
    {
        $this->tonClient = $tonClient;
        $this->app = $app;
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
     * @throws TonException
     */
    public function __invoke(int $requestId, CData $paramsJson, int $responseType, bool $finished): void
    {
        $result = $this->tonClient->getBinding()->getEncoder()->decodeToArray($paramsJson);

        if ($responseType === ResponseType::SUCCESS) {
            $this->handleSuccess($requestId, $result, $finished);
        } elseif ($responseType === ResponseType::ERROR) {
            $this->handleError($requestId, $result);
        } elseif ($responseType >= ResponseType::CUSTOM) {
            $this->handleData($requestId, $result, $finished);
        } elseif ($responseType === ResponseType::NOP) {
            $this->handleNop($requestId, $finished);
        } elseif ($responseType === ResponseType::APP_REQUEST) {
            $this->handleAppRequest($result);
        } elseif ($responseType === ResponseType::APP_NOTIFY) {
            $this->handleAppNotify($result);
        } else {
            throw new DataException(sprintf('Unknown response type %s.', $responseType));
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

    /**
     * @param array<mixed> $result
     */
    public function handleAppRequest(array $result): void
    {
        if ($this->app === null) {
            throw new LogicException('App not defined.');
        }

        if (!isset($result['request_data'], $result['app_request_id'])) {
            throw new DataException('Invalid app data.');
        }

        $app = $this->app;
        $app($this->tonClient, $result['request_data'], $result['app_request_id']);
    }

    /**
     * @param array<mixed> $result
     */
    public function handleAppNotify(array $result): void
    {
        if ($this->app === null) {
            throw new LogicException('App not defined.');
        }

        $app = $this->app;
        $app($this->tonClient, $result);
    }
}
