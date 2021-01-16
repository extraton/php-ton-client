<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfQuery
 */
class ResultOfQuery extends AbstractResult
{
    /**
     * Get result provided by DAppServer
     *
     * @return array<mixed>
     */
    public function getResult(): array
    {
        return $this->requireArray('result');
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->getResult()['data'] ?? [];
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->getData()['messages'] ?? [];
    }
}
