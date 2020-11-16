<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Extraton\TonClient\Exception\EncoderException;
use Extraton\TonClient\Exception\FFIException;
use Extraton\TonClient\FFI\FFIAdapter;
use FFI\CData;
use FFI\Exception;
use JsonException;
use stdClass;

use function json_decode;
use function json_encode;
use function strlen;

use const JSON_THROW_ON_ERROR;

/**
 * Encoder
 */
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
     * @throws EncoderException
     */
    public function encodeArray(array $value): CData
    {
        try {
            $json = (string)json_encode(
                empty($value) ? new stdClass() : $value,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new EncoderException($exception);
        }

        return $this->encodeString($json);
    }

    /**
     * Create CData of tc_string_data_t via FFI
     *
     * @param string $value
     * @return CData
     * @throws FFIException
     */
    public function encodeString(string $value): CData
    {
        try {
            $cData = $this->ffiAdapter->callNew('tc_string_data_t');

            $size = strlen($value);
            // @phpstan-ignore-next-line
            $cData->content = $this->ffiAdapter->callNew("char[{$size}]", false);
            // @phpstan-ignore-next-line
            $cData->len = $size;

            $content = &$cData->content;
            $this->ffiAdapter->callMemCpy($content, $value, $size);

            return $cData;
        } catch (Exception $exception) {
            throw new FFIException($exception);
        }
    }

    /**
     * @param CData $cData
     * @return array<mixed>
     * @throws EncoderException
     */
    public function decodeToArray(CData $cData): array
    {
        try {
            $content = &$cData->content;
            // @phpstan-ignore-next-line
            $size = $cData->len;

            $json = $this->ffiAdapter->callString($content, $size);
        } catch (Exception $exception) {
            throw new FFIException($exception);
        }

        if (empty($json)) {
            return [];
        }

        try {
            return (array)json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new EncoderException($exception);
        }
    }
}
