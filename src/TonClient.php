<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Entity\Client\ResultOfBuildInfo;
use Extraton\TonClient\Entity\Client\ResultOfGetApiReference;
use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Handler\ResponseHandler;
use Extraton\TonClient\Handler\SmartSleeper;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;

/**
 * Ton client
 */
class TonClient
{
    private array $configuration;

    private Binding $binding;

    private ?int $context = null;

    private ?ResponseHandler $responseHandler = null;

    private ?Utils $utils = null;

    private ?Net $net = null;

    private ?Boc $boc = null;

    /**
     * @param array $configuration
     * @param Binding|null $binding
     */
    public function __construct(array $configuration, ?Binding $binding = null)
    {
        $this->configuration = $configuration;
        $this->binding = $binding ?? Binding::createDefault();
    }

    public function getUtils(): Utils
    {
        if ($this->utils === null) {
            $this->utils = new Utils($this);
        }

        return $this->utils;
    }

    public function getNet(): Net
    {
        if ($this->net === null) {
            $this->net = new Net($this);
        }

        return $this->net;
    }

    public function getBoc(): Boc
    {
        if ($this->boc === null) {
            $this->boc = new Boc($this);
        }

        return $this->boc;
    }

    public function version(): ResultOfVersion
    {
        return new ResultOfVersion(
            $this->request(
                'client.version'
            )->wait()
        );
    }

    /**
     * @param string $functionName
     * @param array $functionParams
     * @return Promise
     */
    public function request(string $functionName, array $functionParams = []): Promise
    {
        $responseHandler = $this->getResponseHandler();
        $promise = new Promise(
            static function () use (&$promise) {
                $sleeper = new SmartSleeper();

                while (Is::pending($promise)) {
                    $sleeper->sleep();
                    $sleeper->increase();
                }
            }
        );

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

    public function buildInfo(): ResultOfBuildInfo
    {
        return new ResultOfBuildInfo(
            $this->request(
                'client.build_info'
            )->wait()
        );
    }

    public function getApiReference(): ResultOfGetApiReference
    {
        return new ResultOfGetApiReference(
            $this->request(
                'client.get_api_reference'
            )->wait()
        );
    }
}
