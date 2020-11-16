<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfMnemonicVerify
 */
class ResultOfMnemonicVerify extends AbstractResult
{
    /**
     * Get flag indicating the mnemonic is valid or not
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->requireBool('valid');
    }
}
