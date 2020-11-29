<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetBocHash
 */
class ResultOfGetBocHash extends AbstractResult
{
    /**
     * Get BOC root hash encoded with hex
     *
     * @return mixed
     */
    public function getHash()
    {
        return $this->requireString('hash');
    }
}
