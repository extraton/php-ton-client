<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

/**
 * Type CacheType
 */
class CacheType implements Params
{
    public const TYPE_PINNED = 'Pinned';

    public const TYPE_UNPINNED = 'Unpinned';

    private string $type;

    private string $pin;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create cache type pinned
     *
     * @param string $pin Pin name
     * @return self
     */
    public static function fromPinned(string $pin): self
    {
        $instance = new self(self::TYPE_PINNED);
        $instance->setPin($pin);

        return $instance;
    }

    /**
     * Create cache type unpinned
     *
     * @return self
     */
    public static function fromUnpinned(): self
    {
        return new self(self::TYPE_UNPINNED);
    }

    /**
     * @param string $pin
     */
    public function setPin(string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        if (!in_array($this->type, [self::TYPE_PINNED, self::TYPE_UNPINNED], true)) {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        $result = [
            'type' => $this->type,
        ];

        if ($this->type === self::TYPE_PINNED) {
            $result['pin'] = $this->pin;
        }

        return $result;
    }
}
