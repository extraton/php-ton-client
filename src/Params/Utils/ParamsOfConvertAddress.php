<?php

declare(strict_types=1);

namespace Extraton\TonClient\Params\Utils;

use JsonSerializable;
use RuntimeException;

use function in_array;

class ParamsOfConvertAddress implements JsonSerializable
{
    public const TYPE_ACCOUNT_ID = 'AccountId';

    public const TYPE_HEX = 'Hex';

    public const TYPE_BASE64 = 'Base64';

    private string $address;

    private string $type;

    private bool $url;

    private bool $test;

    private bool $bounce;

    public function __construct(
        string $address,
        string $type,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ) {
        if (!in_array($type, [self::TYPE_ACCOUNT_ID, self::TYPE_HEX, self::TYPE_BASE64], true)) {
            throw new RuntimeException('Unknown address type');
        }

        $this->address = $address;
        $this->type = $type;
        $this->url = $url;
        $this->test = $test;
        $this->bounce = $bounce;
    }

    public function jsonSerialize(): array
    {
        $result = [
            'address'       => $this->address,
            'output_format' => [
                'type' => $this->type,
            ]
        ];

        if ($this->type === self::TYPE_BASE64) {
            $result['output_format']['url'] = $this->url;
            $result['output_format']['test'] = $this->test;
            $result['output_format']['bounce'] = $this->bounce;
        }

        return $result;
    }
}
