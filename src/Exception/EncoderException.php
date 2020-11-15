<?php

declare(strict_types=1);

namespace Extraton\TonClient\Exception;

use RuntimeException;
use Throwable;

/**
 * Wrapper exception with TonException interface for \JsonException
 */
class EncoderException extends RuntimeException implements TonException
{
    /**
     * @param Throwable $previous
     */
    public function __construct(Throwable $previous)
    {
        parent::__construct(
            $previous->getMessage(),
            $previous->getCode(),
            $previous
        );
    }
}
