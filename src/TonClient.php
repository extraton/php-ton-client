<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Handler\ResponseHandler;
use Extraton\TonClient\Request\Client\ResultOfBuildInfo;
use Extraton\TonClient\Request\Client\ResultOfGetApiReference;
use Extraton\TonClient\Request\Client\ResultOfVersion;
use GuzzleHttp\Promise\Promise;

class TonClient
{
    private array $configuration;

    private Binding $binding;

    private ?int $context = null;

    private ?ResponseHandler $responseHandler = null;

    private ?Utils $utils = null;

    /**
     * @param array $configuration
     * @param Binding $binding
     */
    public function __construct(array $configuration, Binding $binding)
    {
        $this->configuration = $configuration;
        $this->binding = $binding;
    }

    /**
     * @return ResponseHandler
     */
    public function getResponseHandler(): ResponseHandler
    {
        if ($this->responseHandler === null) {
            $this->responseHandler = new ResponseHandler($this->binding);
        }

        return $this->responseHandler;
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
        $responseHandler = $this->getResponseHandler();
        $promise = new Promise();

        $requestId = $responseHandler->registerPromise($promise);
        $context = $this->getContext();

        $this->binding->request(
            $context,
            $requestId,
            $functionName,
            $functionParams,
            $responseHandler
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
