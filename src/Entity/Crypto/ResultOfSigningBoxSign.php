<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfSigningBoxSign
 */
class ResultOfSigningBoxSign extends AbstractResult
{
    /**
     * Returns data signature (encoded with hex)
     *
     * @return string
     */
    public function getSignature(): string
    {
        return $this->requireString('signature');
    }
}
