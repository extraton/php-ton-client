<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use JsonSerializable;

abstract class AbstractParams implements JsonSerializable
{
    abstract public function jsonSerialize(): array;
}
