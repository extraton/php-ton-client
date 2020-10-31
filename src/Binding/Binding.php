<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Closure;
use Extraton\TonClient\Exception\ContextException;
use Extraton\TonClient\FFI\FFIWrapper;

class Binding
{
    private FFIWrapper $ffiWrapper;

    private Encoder $encoder;

    public function __construct(string $libraryPath)
    {
        $this->ffiWrapper = new FFIWrapper($this->getLibraryInterface(), $libraryPath);
        $this->encoder = new Encoder($this->ffiWrapper);
    }

    /**
     * @param array $configuration
     * @return int
     */
    public function createContext(array $configuration): int
    {
        $stringData = $this->encoder->encodeArray($configuration);
        $stringHandle = $this->ffiWrapper->call(
            'tc_create_context',
            [
                $stringData
            ]
        );

        $resultStringData = $this->ffiWrapper->call(
            'tc_read_string',
            [
                $stringHandle
            ]
        );
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
     * @param array $functionParams
     * @param Closure $responseHandler
     * @return void
     */
    public function request(
        int $context,
        int $requestId,
        string $functionName,
        array $functionParams,
        Closure $responseHandler
    ): void {
        $functionNameStringData = $this->encoder->encodeString($functionName);
        $functionParamsStringData = $this->encoder->encodeArray($functionParams);

        $this->ffiWrapper->call(
            'tc_request',
            [
                $context,
                $functionNameStringData,
                $functionParamsStringData,
                $requestId,
                $responseHandler
            ]
        );
    }

    /**
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

            enum tc_response_types_t {
                tc_response_success = 0,
                tc_response_error = 1,
                tc_response_nop = 2,
                tc_response_custom = 100,
            };

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
}
