<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\ParamsInterface;
use LogicException;

use function in_array;

class OrderBy implements ParamsInterface
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';

    private array $orderBy;

    /**
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function add(string $field, string $direction): self
    {
        if (!in_array($direction, [self::ASC, self::DESC], true)) {
            throw new LogicException('Invalid direction.');
        }

        if (isset($this->orderBy[$field])) {
            throw new LogicException('Already set');
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
