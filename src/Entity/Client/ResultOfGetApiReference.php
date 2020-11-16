<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfGetApiReference
 */
class ResultOfGetApiReference extends AbstractResult
{
    /**
     * Get API information
     *
     * @return array<mixed>
     */
    public function getApi(): array
    {
        return $this->requireArray('api');
    }
}
