<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfHDKeyPublicFromXPrv extends AbstractResult
{
    public function getPublic(): string
    {
        return $this->requireString('public');
    }
}
