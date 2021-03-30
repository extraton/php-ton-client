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

    public const APP_REQUEST = 3;

    public const APP_NOTIFY = 4;

    public const CUSTOM = 100;
}
