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
use JsonException;
use LogicException;

/**
 * Ton client
 */
class TonClient
{
    /** @var array<mixed> */
    private array $configuration;

    private Binding $binding;

    private ?int $context = null;

    private ?ResponseHandler $responseHandler = null;

    private ?Utils $utils = null;

    private ?Net $net = null;

    private ?Boc $boc = null;

    private ?Processing $processing = null;

    private ?Abi $abi = null;

    private ?Crypto $crypto = null;

    /**
     * @param array<mixed> $configuration
     * @param Binding|null $binding
     */
    public function __construct(array $configuration, ?Binding $binding = null)
    {
        $this->configuration = $configuration;
        $this->binding = $binding ?? Binding::createDefault();
    }

    /**
     * Get Utils module
     *
     * @return Utils
     */
    public function getUtils(): Utils
    {
        if ($this->utils === null) {
            $this->utils = new Utils($this);
        }

        return $this->utils;
    }

    /**
     * Get Net module
     *
     * @return Net
     */
    public function getNet(): Net
    {
        if ($this->net === null) {
            $this->net = new Net($this);
        }

        return $this->net;
    }

    /**
     * Get Boc module
     *
     * @return Boc
     */
    public function getBoc(): Boc
    {
        if ($this->boc === null) {
            $this->boc = new Boc($this);
        }

        return $this->boc;
    }

    /**
     * Get message processing module
     *
     * @return Processing
     */
    public function getProcessing(): Processing
    {
        if ($this->processing === null) {
            $this->processing = new Processing($this);
        }

        return $this->processing;
    }

    /**
     * Get Abi module
     *
     * @return Abi
     */
    public function getAbi(): Abi
    {
        if ($this->abi === null) {
            $this->abi = new Abi($this);
        }

        return $this->abi;
    }

    /**
     * Get Crypto module
     *
     * @return Crypto
     */
    public function getCrypto(): Crypto
    {
        if ($this->crypto === null) {
            $this->crypto = new Crypto($this);
        }

        return $this->crypto;
    }

    /**
     * Main method to call sdk methods
     *
     * @param string $functionName Function name
     * @param array<string, mixed> $functionParams Function params
     * @return Promise
     * @throws JsonException
     */
    public function request(string $functionName, array $functionParams = []): Promise
    {
        $responseHandler = $this->getResponseHandler();
        $promise = new Promise(
            static function () use (&$promise) {
                $sleeper = new SmartSleeper();

                if ($promise === null) {
                    throw new LogicException('Unknown condition');
                }

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
     * Get response handler
     *
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
     * Get sdk content
     *
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
     * Get sdk client version
     *
     * @return ResultOfVersion
     */
    public function version(): ResultOfVersion
    {
        return new ResultOfVersion(
            $this->request(
                'client.version'
            )->wait()
        );
    }

    /**
     * Get build info
     *
     * @return ResultOfBuildInfo
     */
    public function buildInfo(): ResultOfBuildInfo
    {
        return new ResultOfBuildInfo(
            $this->request(
                'client.build_info'
            )->wait()
        );
    }

    /**
     * Get api version
     *
     * @return ResultOfGetApiReference
     */
    public function getApiReference(): ResultOfGetApiReference
    {
        return new ResultOfGetApiReference(
            $this->request(
                'client.get_api_reference'
            )->wait()
        );
    }
}
