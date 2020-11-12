<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfMnemonicVerify extends AbstractResult
{
    public function isValid(): bool
    {
        return $this->requireBool('valid');
    }
}
