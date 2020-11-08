<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfGetBlockchainConfig extends AbstractResult
{
    public function getConfigBoc(): string
    {
        return $this->requireString('config_boc');
    }
}
