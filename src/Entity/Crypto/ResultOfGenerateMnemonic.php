<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfGenerateMnemonic extends AbstractResult
{
    public function getPhrase(): string
    {
        return $this->requireString('phrase');
    }
}
