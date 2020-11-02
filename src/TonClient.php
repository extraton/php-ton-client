<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Binding\Type\ResponseType;
use Extraton\TonClient\Exception\RequestException;
use Extraton\TonClient\Request\RequestWatcher;
use Extraton\TonClient\Result\Client\ResultOfBuildInfo;
use Extraton\TonClient\Result\Client\ResultOfGetApiReference;
use Extraton\TonClient\Result\Client\ResultOfVersion;
use FFI\CData;
use GuzzleHttp\Promise\Promise;
use RuntimeException;

class TonClient
{
    private array $configuration;

    private Binding $binding;

    private ?int $context = null;

    private RequestWatcher $requestWatcher;

    private ?Utils $utils = null;

    /**
     * @param array $configuration
     * @param Binding $binding
     */
    public function __construct(array $configuration, Binding $binding)
    {
        $this->configuration = $configuration;
        $this->binding = $binding;
        $this->requestWatcher = new RequestWatcher();
    }

    /**
     * @return int
     */
    public function getContext(): int
    {
        if ($this->context === null) {
            $this->context = $this->binding->createContext($this->configuration);
        }

        return $this->context;
    }

    /**
     * @param string $functionName
     * @param array $functionParams
     * @return Promise
     */
    public function request(string $functionName, array $functionParams = []): Promise
    {
        $requestId = $this->requestWatcher->generateRequestId();

        $promise = new Promise();

        $this->requestWatcher->addPromise($requestId, $promise);
        $context = $this->getContext();

        $this->binding->request(
            $context,
            $requestId,
            $functionName,
            $functionParams,
            function (int $requestId, CData $paramsJson, int $responseType, bool $finished) {
                $result = $this->binding->getEncoder()->decodeToArray($paramsJson);
                $promise = $this->requestWatcher->getPromise($requestId);

                if ($finished) {
                    $this->requestWatcher->removePromise($requestId);
                }

                if ($responseType === ResponseType::SUCCESS) {
                    $promise->resolve($result);
                } elseif ($responseType === ResponseType::ERROR) {
                    $promise->reject(RequestException::create($result));
                } else {
                    $promise->reject(new RuntimeException('Unknown error.'));
                }
            }
        );

        return $promise;
    }

    public function getVersion(): ResultOfVersion
    {
        return new ResultOfVersion($this->request('client.version')->wait());
    }

    public function getBuildInfo(): ResultOfBuildInfo
    {
        return new ResultOfBuildInfo($this->request('client.build_info')->wait());
    }

    public function getApiReference(): ResultOfGetApiReference
    {
        return new ResultOfGetApiReference($this->request('client.get_api_reference')->wait());
    }

    public function getUtils(): Utils
    {
        if ($this->utils === null) {
            $this->utils = new Utils($this);
        }

        return $this->utils;
    }
}
