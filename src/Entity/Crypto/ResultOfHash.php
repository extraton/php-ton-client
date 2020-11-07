<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfHash extends AbstractResult
{
    public function getHash(): string
    {
        return $this->requireString('hash');
    }
}
