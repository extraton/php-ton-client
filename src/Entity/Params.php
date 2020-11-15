<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity;

use Extraton\TonClient\Exception\DataException;
use JsonSerializable;

/**
 * Parameters interface
 */
interface Params extends JsonSerializable
{
    /**
     * @return array<mixed>
     * @throws DataException
     */
    public function jsonSerialize(): array;
}
