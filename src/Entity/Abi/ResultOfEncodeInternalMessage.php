<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncodeInternalMessage
 */
class ResultOfEncodeInternalMessage extends AbstractResult
{
    /**
     * Get message BOC encoded with base64
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->requireString('message');
    }

    /**
     * Get destination address
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->requireString('address');
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
