<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

use function in_array;
use function sprintf;

/**
 * Query OrderBy parameters
 */
class OrderBy implements Params
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';

    /** @var array<string, string> */
    private array $orderBy;

    /**
     * Add OrderBy condition
     *
     * @param string $field
     * @param string $direction
     * @return self
     * @throws LogicException
     */
    public function add(string $field, string $direction): self
    {
        if (!in_array($direction, [self::ASC, self::DESC], true)) {
            throw new LogicException(sprintf('Invalid direction %s.', $direction));
        }

        if (isset($this->orderBy[$field])) {
            throw new LogicException(sprintf('OrderBy field %s already defined.', $field));
        }

        $this->orderBy[$field] = $direction;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $orderBy = [];

        foreach ($this->orderBy as $field => $direction) {
            $orderBy[] = [
                'path'      => $field,
                'direction' => $direction,
            ];
        }

        return $orderBy;
    }
}
