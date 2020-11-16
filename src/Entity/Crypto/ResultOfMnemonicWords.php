<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfMnemonicWords
 */
class ResultOfMnemonicWords extends AbstractResult
{
    /**
     * Get list of mnemonic words
     *
     * @return string
     */
    public function getWords(): string
    {
        return $this->requireString('words');
    }
}
