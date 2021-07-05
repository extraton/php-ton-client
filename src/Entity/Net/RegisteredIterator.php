<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type RegisteredIterator
 */
class RegisteredIterator extends AbstractResult
{
    /**
     * Get handle
     *
     * @return int
     */
    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }
}
