<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncodeMessage
 */
class ResultOfEncodeMessage extends AbstractResult
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
     * Get optional data to be signed encoded in base64
     *
     * @return string|null
     */
    public function getDataToSign(): ?string
    {
        return $this->getString('data_to_sign');
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
