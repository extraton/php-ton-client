<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncodeMessageBody
 */
class ResultOfEncodeMessageBody extends AbstractResult
{
    /**
     * Get message body BOC encoded with base64
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->requireString('body');
    }

    /**
     * Get data to sign, optional, encoded with base64
     *
     * @return string|null
     */
    public function getDataToSign(): ?string
    {
        return $this->getString('data_to_sign');
    }
}
