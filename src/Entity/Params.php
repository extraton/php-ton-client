<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity;

use JsonSerializable;

/**
 * Parameters interface
 */
interface Params extends JsonSerializable
{
    /**
     * @return array<mixed>
     */
    public function jsonSerialize(): array;
}
