<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type RegisteredEncryptionBox
 */
class RegisteredEncryptionBox extends AbstractResult
{
    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }
}
