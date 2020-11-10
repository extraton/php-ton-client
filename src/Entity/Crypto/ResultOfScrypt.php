<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfScrypt extends AbstractResult
{
    public function getKey(): string
    {
        return $this->requireString('key');
    }
}
