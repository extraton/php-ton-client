<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfHDKeyXPrv
 */
class ResultOfHDKeyXPrv extends AbstractResult
{
    /**
     * Get serialized extended private / master private key
     *
     * @return string
     */
    public function getXprv(): string
    {
        return $this->requireString('xprv');
    }
}
