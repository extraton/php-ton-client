<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\ParamsInterface;
use LogicException;
use RuntimeException;

use function in_array;

/**
 * Filter collection
 */
class Filters implements ParamsInterface
{
    public const EQ = 'eq';

    public const GE = 'ge';

    public const GT = 'gt';

    public const IN = 'in';

    public const LE = 'le';

    public const LT = 'lt';

    public const NE = 'ne';

    public const NOT_IN = 'notIn';

    public const OPERATORS = [
        self::EQ,
        self::GE,
        self::GT,
        self::IN,
        self::LE,
        self::LT,
        self::NE,
        self::NOT_IN,
    ];

    private array $filters = [];

    /**
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return $this
     */
    public function add(string $field, string $operator, $value): self
    {
        if (isset($this->filters[$field])) {
            throw new LogicException('Already exists.');
        }

        if (!in_array($operator, self::OPERATORS, true)) {
            throw new RuntimeException('Unknown operator.');
        }

        $this->filters[$field] = [
            $operator => $value,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->filters;
    }
}
