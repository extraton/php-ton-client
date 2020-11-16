<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGenerateMnemonic
 */
class ResultOfGenerateMnemonic extends AbstractResult
{
    /**
     * Get string of mnemonic words
     *
     * @return string
     */
    public function getPhrase(): string
    {
        return $this->requireString('phrase');
    }
}
