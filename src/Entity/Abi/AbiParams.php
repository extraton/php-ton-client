<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;
use JsonException;

use function json_decode;

use const JSON_THROW_ON_ERROR;

class AbiParams implements ParamsInterface
{
    public const TYPE_SERIALIZED = 'Serialized';

    public const TYPE_HANDLE = 'Handle';

    private string $type;

    /** @var array|int */
    private $value;

    /**
     * @param string $type
     * @param array|int $value
     */
    public function __construct(string $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @param string $json
     * @return static
     * @throws JsonException
     */
    public static function fromJson(string $json): self
    {
        return new self(
            self::TYPE_SERIALIZED,
            json_decode($json, true, 32, JSON_THROW_ON_ERROR)
        );
    }

    /**
     * @param array $value
     * @return static
     */
    public static function fromArray(array $value): self
    {
        return new self(
            self::TYPE_SERIALIZED,
            $value
        );
    }

    /**
     * @param int $handle
     * @return static
     */
    public static function fromHandle(int $handle): self
    {
        return new self(
            self::TYPE_HANDLE,
            $handle
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'type'  => $this->type,
            'value' => $this->value,
        ];
    }
}
