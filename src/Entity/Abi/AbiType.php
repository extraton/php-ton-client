<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;
use Extraton\TonClient\Exception\EncoderException;

use function sprintf;

/**
 * Type Abi
 */
class AbiType implements Params
{
    public const TYPE_SERIALIZED = 'Serialized';

    public const TYPE_HANDLE = 'Handle';

    public const TYPE_JSON = 'Json';

    private string $type;

    private int $handle;

    /** @var array<mixed> */
    private array $arrayValue;

    private string $json;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create Abi object from json
     *
     * @param string $json Abi josn
     * @return self
     * @throws EncoderException
     */
    public static function fromJson(string $json): self
    {
        $instance = new self(self::TYPE_JSON);
        $instance->setJson($json);

        return $instance;
    }

    /**
     * Create Abi object from array
     *
     * @param array<mixed> $arrayValue
     * @return self
     */
    public static function fromArray(array $arrayValue): self
    {
        $instance = new self(self::TYPE_SERIALIZED);
        $instance->setArrayValue($arrayValue);

        return $instance;
    }

    /**
     * Create Abi object from handle
     *
     * @param int $handle Handle
     * @return self
     */
    public static function fromHandle(int $handle): self
    {
        $instance = new self(self::TYPE_HANDLE);
        $instance->setHandle($handle);

        return $instance;
    }

    /**
     * Set array value
     *
     * @param array<mixed> $arrayValue
     * @return self
     */
    private function setArrayValue(array $arrayValue): self
    {
        $this->arrayValue = $arrayValue;

        return $this;
    }

    /**
     * Set json value
     *
     * @param string $json
     * @return self
     */
    private function setJson(string $json): self
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Set handle
     *
     * @param int $handle Handle
     * @return self
     */
    private function setHandle(int $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $result['type'] = $this->type;

        if ($this->type === self::TYPE_HANDLE) {
            $result['value'] = $this->handle;
        } elseif ($this->type === self::TYPE_SERIALIZED) {
            $result['value'] = $this->arrayValue;
        } elseif ($this->type === self::TYPE_JSON) {
            $result['value'] = $this->json;
        } else {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        return $result;
    }
}
