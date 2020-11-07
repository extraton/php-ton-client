<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity;

use JsonSerializable;

interface ParamsInterface extends JsonSerializable
{
    public function jsonSerialize(): array;
}
