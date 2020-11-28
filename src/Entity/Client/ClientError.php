<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ClientError
 */
class ClientError extends AbstractResult
{
    /**
     * Get error code
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->requireInt('code');
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->requireString('message');
    }

    /**
     * Get error data
     *
     * @return mixed
     */
    public function getErrorData()
    {
        return $this->getOriginData('data');
    }
}
