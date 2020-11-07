<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfGenerateSignKeys extends AbstractResult
{
    public function getKeyPair(): KeyPair
    {
        return new KeyPair($this->requireString('public'), $this->requireString('secret'));
    }
}
