<?php

declare(strict_types=1);

namespace Extraton\TonClient\Exception;

use RuntimeException;

/**
 * Context exception
 */
class ContextException extends RuntimeException implements TonException
{
    public function __construct()
    {
        parent::__construct('Failed to create TON SDK context');
    }
}
