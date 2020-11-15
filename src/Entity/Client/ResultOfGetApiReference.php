<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfGetApiReference extends AbstractResult
{
    /**
     * @return array<mixed>
     */
    public function getApi(): array
    {
        return $this->requireArray('api');
    }
}
