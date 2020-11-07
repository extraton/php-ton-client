<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use LogicException;

use function in_array;

class OrderByCollection implements OrderByCollectionInterface
{
    public const ASC = 'ASC';

    public const DESC = 'DESC';

    private array $orderBy;

    /**
     * @param string $path
     * @param string $direction
     * @return $this
     */
    public function add(string $path, string $direction): self
    {
        if (!in_array($direction, [self::ASC, self::DESC], true)) {
            throw new LogicException('Invalid direction.');
        }

        $this->orderBy[] = [
            'path'      => $path,
            'direction' => $direction
        ];

        return $this;
    }

    public function jsonSerialize(): array
    {
        return $this->orderBy;
    }
}
