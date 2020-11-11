<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Extraton\TonClient\FFI\FFIAdapter;
use FFI\CData;
use JsonException;
use stdClass;

use function json_decode;
use function json_encode;
use function strlen;

use const JSON_THROW_ON_ERROR;

class Encoder
{
    private FFIAdapter $ffiAdapter;

    /**
     * @param FFIAdapter $ffiAdapter
     */
    public function __construct(FFIAdapter $ffiAdapter)
    {
        $this->ffiAdapter = $ffiAdapter;
    }

    /**
     * Create CData of tc_string_data_t via FFI
     *
     * @param array<mixed> $value
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
        $cData = $this->ffiAdapter->callNew('tc_string_data_t');

        $size = strlen($value);
        // @phpstan-ignore-next-line
        $cData->content = $this->ffiAdapter->callNew("char[{$size}]", false);
        // @phpstan-ignore-next-line
        $cData->len = $size;

        $content = &$cData->content;
        $this->ffiAdapter->callMemCpy($content, $value, $size);

        return $cData;
    }

    /**
     * @param CData $cData
     * @return array<mixed>
     * @throws JsonException
     */
    public function decodeToArray(CData $cData): array
    {
        $content = &$cData->content;
        // @phpstan-ignore-next-line
        $size = $cData->len;

        $json = $this->ffiAdapter->callString($content, $size);

        if (empty($json)) {
            return [];
        }

        return (array)json_decode($json, true, 32, JSON_THROW_ON_ERROR);
    }
}
