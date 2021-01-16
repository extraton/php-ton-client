<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type EndpointsSet
 */
class EndpointsSet extends AbstractResult
{
    /**
     * @return array<string>
     */
    public function getEndpoints(): array
    {
        return $this->requireArray('endpoints');
    }
}
