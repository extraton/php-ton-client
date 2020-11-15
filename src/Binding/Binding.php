<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Extraton\TonClient\Exception\ConfigException;
use Extraton\TonClient\Exception\ContextException;
use Extraton\TonClient\Exception\EncoderException;
use Extraton\TonClient\Exception\FFIException;
use Extraton\TonClient\FFI\FFIAdapter;
use FFI\Exception;

use function file_exists;
use function sprintf;
use function str_replace;
use function strtolower;
use function usleep;

use const DIRECTORY_SEPARATOR;
use const PHP_OS;

/**
 * Low-level binding to TON SDK library
 */
class Binding
{
    private FFIAdapter $ffiAdapter;

    private Encoder $encoder;

    /**
     * @param string $libraryPath
     */
    public function __construct(string $libraryPath)
    {
        $this->ffiAdapter = new FFIAdapter($this->getLibraryInterface(), $libraryPath);
        $this->encoder = new Encoder($this->ffiAdapter);
    }

    /**
     * Get TON SDK library interface
     *
     * @return string
     */
    public function getLibraryInterface(): string
    {
        return <<<C
            typedef struct {
                const char* content;
                uint32_t len;
            } tc_string_data_t;

            typedef struct  {
            } tc_string_handle_t;
            
            typedef void (*tc_response_handler_t)(
            uint32_t request_id,
            tc_string_data_t params_json,
            uint32_t response_type,
            bool finished);

            tc_string_data_t tc_read_string(const tc_string_handle_t* string);
            void tc_destroy_string(const tc_string_handle_t* string);
            
            tc_string_handle_t* tc_create_context(tc_string_data_t config);
            void tc_destroy_context(uint32_t context);

            void tc_request(
            uint32_t context,
            tc_string_data_t function_name,
            tc_string_data_t function_params_json,
            uint32_t request_id,
            tc_response_handler_t response_handler);
        C;
    }

    /**
     * Create default binding
     *
     * @return self
     * @throws ConfigException
     */
    public static function createDefault(): self
    {
        $paths = [
            'linux'  => __DIR__ . '/../../bin/tonclient.so',
            'darwin' => __DIR__ . '/../../bin/tonclient.dylib',
            'win32'  => __DIR__ . '/../../bin/tonclient.dll',
        ];

        $os = strtolower(PHP_OS);

        if (!isset($paths[$os])) {
            throw new ConfigException(sprintf('TON SDK library not found by OS %s.', $os));
        }

        $path = str_replace('/', DIRECTORY_SEPARATOR, $paths[$os]);

        if (!file_exists($path)) {
            throw new ConfigException(sprintf('TON SDK library not found by path %s.', $paths[$os]));
        }

        return new self($path);
    }

    /**
     * @return Encoder
     */
    public function getEncoder(): Encoder
    {
        return $this->encoder;
    }

    /**
     * @param array<mixed> $configuration
     * @return int
     * @throws EncoderException
     * @throws FFIException
     */
    public function createContext(array $configuration): int
    {
        $stringData = $this->encoder->encodeArray($configuration);

        try {
            $stringHandle = $this->ffiAdapter->call(
                'tc_create_context',
                [
                    $stringData
                ]
            );

            $resultStringData = $this->ffiAdapter->call(
                'tc_read_string',
                [
                    $stringHandle
                ]
            );
        } catch (Exception $exception) {
            throw new FFIException($exception);
        }

        $result = $this->encoder->decodeToArray($resultStringData);

        if (!empty($result['result']) && $result['result'] > 0) {
            return $result['result'];
        }

        throw new ContextException();
    }

    /**
     * @param int $context
     * @param int $requestId
     * @param string $functionName
     * @param array<string, mixed> $functionParams
     * @param callable $responseHandler
     * @throws EncoderException
     * @throws FFIException
     */
    public function request(
        int $context,
        int $requestId,
        string $functionName,
        array $functionParams,
        callable $responseHandler
    ): void {
        $functionNameStringData = $this->encoder->encodeString($functionName);
        $functionParamsStringData = $this->encoder->encodeArray($functionParams);

        try {
            $this->ffiAdapter->call(
                'tc_request',
                [
                    $context,
                    $functionNameStringData,
                    $functionParamsStringData,
                    $requestId,
                    $responseHandler
                ]
            );
        } catch (Exception $exception) {
            throw new FFIException($exception);
        }

        // Protect segfault
        usleep(25_000);
    }
}
