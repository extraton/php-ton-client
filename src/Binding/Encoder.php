<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Extraton\TonClient\FFI\FFIWrapper;
use FFI\CData;
use JsonException;
use stdClass;

use function json_decode;
use function json_encode;
use function strlen;

use const JSON_THROW_ON_ERROR;

class Encoder
{
    private FFIWrapper $ffiWrapper;

    /**
     * @param FFIWrapper $ffiWrapper
     */
    public function __construct(FFIWrapper $ffiWrapper)
    {
        $this->ffiWrapper = $ffiWrapper;
    }

    /**
     * Create CData of tc_string_data_t via FFI
     *
     * @param array $value
     * @return CData
     * @throws JsonException
     */
    public function encodeArray(array $value): CData
    {
        $json = (string)json_encode(
            empty($value) ? new stdClass() : $value,
            JSON_THROW_ON_ERROR
        );

        return $this->encodeString($json);
    }

    /**
     * Create CData of tc_string_data_t via FFI
     *
     * @param string $value
     * @return CData
     */
    public function encodeString(string $value): CData
    {
        $cData = $this->ffiWrapper->callNew('tc_string_data_t');

        $size = strlen($value);
        $cData->content = $this->ffiWrapper->callNew("char[{$size}]", false);
        $cData->len = $size;

        $content = &$cData->content;
        $this->ffiWrapper->callMemCpy($content, $value, $size);

        return $cData;
    }

    /**
     * @param CData $cData
     * @return array
     * @throws JsonException
     */
    public function decodeToArray(CData $cData): array
    {
        $content = &$cData->content;
        $size = $cData->len;

        $json = $this->ffiWrapper->callString($content, $size);

        return (array)json_decode($json, true, 32, JSON_THROW_ON_ERROR);
    }
}
