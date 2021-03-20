<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

use function in_array;
use function sprintf;

/**
 * Type Aggregation
 */
class Aggregation implements Params
{
    public const COUNT = 'COUNT';

    public const MIN = 'MIN';

    public const MAX = 'MAX';

    public const SUM = 'SUM';

    public const AVERAGE = 'AVERAGE';

    public const FUNCTIONS = [
        self::COUNT,
        self::MIN,
        self::MAX,
        self::SUM,
        self::AVERAGE,
    ];

    /** @var array<array<string, string>> */
    private array $fields = [];

    /**
     * @param string $field
     * @param string $function
     * @return self
     */
    public function add(string $field, string $function): self
    {
        if (!in_array($function, self::FUNCTIONS, true)) {
            throw new LogicException(sprintf('Unknown aggregation function %s.', $function));
        }

        $this->fields[] = [
            'field' => $field,
            'fn'    => $function,
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->fields;
    }
}
