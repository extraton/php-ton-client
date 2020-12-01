<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Entity\Client\ResultOfBuildInfo;
use Extraton\TonClient\Entity\Client\ResultOfGetApiReference;
use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Exception\LogicException;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\Handler\ResponseHandler;
use Extraton\TonClient\Handler\SmartSleeper;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;

use function sprintf;

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

    private ?Tvm $tvm = null;

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
     * Create TonClient with default configuration
     *
     * @return self
     */
    public static function createDefault(): self
    {
        $config = [
            'network' => [
                'server_address'             => 'net.ton.dev',
                'network_retries_count'      => 5,
                'message_retries_count'      => 5,
                'message_processing_timeout' => 300000,
                'wait_for_timeout'           => 300000,
                'out_of_sync_threshold'      => 150000,
                'access_key'                 => ''
            ],
            'abi'     => [
                'workchain'                              => 0,
                'message_expiration_timeout'             => 300000,
                'message_expiration_timeout_grow_factor' => 1.25
            ],
            'crypto'  => [
                'mnemonic_dictionary'   => 1,
                'mnemonic_word_count'   => 12,
                'hdkey_derivation_path' => "m/44'/396'/0'/0/0",
                'hdkey_compliant'       => true
            ],
        ];

        return new TonClient($config);
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
     * Get Tvm module
     *
     * @return Tvm
     */
    public function getTvm(): Tvm
    {
        if ($this->tvm === null) {
            $this->tvm = new Tvm($this);
        }

        return $this->tvm;
    }

    /**
     * Main method to call sdk methods
     *
     * @param string $functionName Function name
     * @param array<string, mixed> $functionParams Function params
     * @return Promise
     * @throws TonException
     */
    public function request(string $functionName, array $functionParams = []): Promise
    {
        $responseHandler = $this->getResponseHandler();
        $promise = new Promise(
            static function () use (&$promise, $functionName) {
                $sleeper = new SmartSleeper();

                if ($promise === null) {
                    throw new LogicException(sprintf('Promise for the %s function not found.', $functionName));
                }

                while (Is::pending($promise)) {
                    $sleeper->sleep()->increase();
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
     * @throws TonException
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
     * @throws TonException
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
     * @throws TonException
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
