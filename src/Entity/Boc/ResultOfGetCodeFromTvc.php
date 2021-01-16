<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetCodeFromTvc
 */
class ResultOfGetCodeFromTvc extends AbstractResult
{
    /**
     * Get contract code encoded as base64
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->requireString('code');
    }
}
