<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding;

use Extraton\TonClient\FFI\FFIWrapper;
use FFI\CData;
use stdClass;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use function strlen;

class Encoder
{
    private FFIWrapper $ffiWrapper;

    private JsonEncode $jsonEncode;

    private JsonDecode $jsonDecode;

    /**
     * @param FFIWrapper $ffiWrapper
     */
    public function __construct(FFIWrapper $ffiWrapper)
    {
        $this->ffiWrapper = $ffiWrapper;
        $this->jsonEncode = new JsonEncode();
        $this->jsonDecode = new JsonDecode(
            [
                JsonDecode::ASSOCIATIVE => true
            ]
        );
    }

    /**
     * Create CData of tc_string_data_t via FFI
     *
     * @param array $value
     * @return CData
     */
    public function encodeArray(array $value): CData
    {
        $json = $this->jsonEncode->encode(
            empty($value) ? new stdClass() : $value,
            JsonEncoder::FORMAT
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
        $stringData = $this->ffiWrapper->callNew('tc_string_data_t');

        $size = strlen($value);
        $stringData->content = $this->ffiWrapper->callNew("char[{$size}]", false);
        $stringData->len = $size;

        $content = &$stringData->content;
        $this->ffiWrapper->callMemCpy($content, $value, $size);

        return $stringData;
    }

    /**
     * @param CData $data
     * @return array
     */
    public function decodeToArray(CData $data): array
    {
        $content = &$data->content;
        $size = $data->len;

        $json = $this->ffiWrapper->callString($content, $size);

        return (array)$this->jsonDecode->decode(
            $json,
            JsonEncoder::FORMAT
        );
    }
}
