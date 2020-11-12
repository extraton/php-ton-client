<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfMnemonicWords extends AbstractResult
{
    public function getWords(): string
    {
        return $this->requireString('words');
    }
}
