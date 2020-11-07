<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use JsonSerializable;

interface FilterCollectionInterface extends JsonSerializable
{
    public function jsonSerialize(): array;
}
