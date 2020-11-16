<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfAttachSignature
 */
class ResultOfAttachSignature extends AbstractResult
{
    /**
     * Get signed message BOC
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->requireString('message');
    }

    /**
     * Get message ID
     *
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->requireString('message_id');
    }
}
