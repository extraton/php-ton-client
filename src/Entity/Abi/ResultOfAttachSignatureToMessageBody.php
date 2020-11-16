<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfAttachSignatureToMessageBody
 */
class ResultOfAttachSignatureToMessageBody extends AbstractResult
{
    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->requireString('body');
    }
}
