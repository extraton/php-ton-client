<?php

declare(strict_types=1);

namespace Extraton\TonClient\Exception;

use RuntimeException;

class ContextException extends RuntimeException implements TonException
{
    public function __construct()
    {
        parent::__construct("Create context TON SDK failed.");
    }
}
