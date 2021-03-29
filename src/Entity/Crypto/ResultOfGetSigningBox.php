<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetSigningBox
 */
class ResultOfGetSigningBox extends AbstractResult
{
    /**
     * Returns handle of the signing box
     *
     * @return int
     */
    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }
}
