<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\App\AppInterface;
use Extraton\TonClient\Binding\Binding;
use Extraton\TonClient\Entity\App\AppRequestResult;
use Extraton\TonClient\Entity\Client\ResultOfBuildInfo;
use Extraton\TonClient\Entity\Client\ResultOfGetApiReference;
use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Exception\LogicException;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\Handler\ResponseHandler;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;

use function sprintf;
use function usleep;

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

    private ?Debot $debot = null;

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
                'endpoints'                  => [
                    'https://net1.ton.dev/',
                    'https://net5.ton.dev/',
                ],
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
     * @return Binding
     */
    public function getBinding(): Binding
    {
        return $this->binding;
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
     * Get Debot module
     *
     * @return Debot
     */
    public function getDebot(): Debot
    {
        if ($this->debot === null) {
            $this->debot = new Debot($this);
        }

        return $this->debot;
    }

    /**
     * Main method to call sdk methods
     *
     * @param string $functionName Function name
     * @param array<string, mixed> $functionParams Function params
     * @param AppInterface|null $app
     * @return Promise
     * @throws TonException
     */
    public function request(string $functionName, array $functionParams = [], AppInterface $app = null): Promise
    {
        $responseHandler = $this->getResponseHandler($app);
        $promise = new Promise(
            static function () use (&$promise, $functionName) {
                if ($promise === null) {
                    throw new LogicException(sprintf('Promise for the %s function not found.', $functionName));
                }

                while (Is::pending($promise)) {
                    usleep(500_000);
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
     * @param AppInterface|null $app
     * @return ResponseHandler
     */
    public function getResponseHandler(AppInterface $app = null): ResponseHandler
    {
        if ($this->responseHandler === null) {
            $this->responseHandler = new ResponseHandler($this, $app);
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

    /**
     * Resolves application request processing result
     *
     * @param int $appRequestId Request ID received from SDK
     * @param AppRequestResult $appRequestResult Result of request processing
     * @throws TonException
     */
    public function resolveAppRequest(int $appRequestId, AppRequestResult $appRequestResult): void
    {
        $this->request(
            'client.resolve_app_request',
            [
                'app_request_id' => $appRequestId,
                'result'         => $appRequestResult,
            ]
        )->wait();
    }
}
