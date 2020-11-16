<?php

declare(strict_types=1);

namespace Extraton\TonClient\Binding\Type;

/**
 * ResponseType
 */
class ResponseType
{
    public const SUCCESS = 0;

    public const ERROR = 1;

    public const NOP = 2;

    public const CUSTOM = 100;
}
