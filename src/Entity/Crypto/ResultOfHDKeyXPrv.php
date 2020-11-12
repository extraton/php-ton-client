<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfHDKeyXPrv extends AbstractResult
{
    public function getXprv(): string
    {
        return $this->requireString('xprv');
    }
}
