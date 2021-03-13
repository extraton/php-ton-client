<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

/**
 * Type BuilderOp
 */
class BuilderOp implements Params
{
    public const TYPE_INTEGER = 'Integer';

    public const TYPE_BIT_STRING = 'BitString';

    public const TYPE_CELL = 'Cell';

    public const TYPE_CELL_BOC = 'CellBoc';

    private string $type;

    private int $size;

    /** @var mixed */
    private $value;

    private string $boc;

    private string $bitString;

    /** @var array<BuilderOp> */
    private array $builderOps;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param int $size
     * @param mixed $value
     * @return self
     */
    public static function fromInteger(int $size, $value): self
    {
        $instance = new self(self::TYPE_INTEGER);
        $instance->setSize($size);
        $instance->setValue($value);

        return $instance;
    }

    /**
     * @param string $bitString
     * @return self
     */
    public static function fromBitString(string $bitString): self
    {
        $instance = new self(self::TYPE_BIT_STRING);
        $instance->setBitString($bitString);

        return $instance;
    }

    /**
     * @param array<BuilderOp> $builderOps
     * @return self
     */
    public static function fromBuilderOps(array $builderOps): self
    {
        $instance = new self(self::TYPE_CELL);
        $instance->setBuilderOps($builderOps);

        return $instance;
    }

    /**
     * @param string $boc
     * @return self
     */
    public static function fromCellBoc(string $boc): self
    {
        $instance = new self(self::TYPE_CELL);
        $instance->setBoc($boc);

        return $instance;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @param string $bitString
     */
    public function setBitString(string $bitString): void
    {
        $this->bitString = $bitString;
    }

    /**
     * @param string $boc
     */
    public function setBoc(string $boc): void
    {
        $this->boc = $boc;
    }

    /**
     * @param array<BuilderOp> $builderOps
     */
    public function setBuilderOps(array $builderOps): void
    {
        $this->builderOps = $builderOps;
    }

    public function jsonSerialize(): array
    {
        if (!in_array(
            $this->type,
            [
                self::TYPE_INTEGER,
                self::TYPE_BIT_STRING,
                self::TYPE_CELL,
                self::TYPE_CELL_BOC
            ],
            true
        )) {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        $result = [
            'type' => $this->type,
        ];

        if ($this->type === self::TYPE_INTEGER) {
            $result['size'] = $this->size;
            $result['value'] = $this->value;
        }

        if ($this->type === self::TYPE_BIT_STRING) {
            $result['value'] = $this->bitString;
        }

        if ($this->type === self::TYPE_CELL) {
            $result['builder'] = $this->builderOps;
        }

        if ($this->type === self::TYPE_CELL_BOC) {
            $result['boc'] = $this->boc;
        }

        return $result;
    }
}
