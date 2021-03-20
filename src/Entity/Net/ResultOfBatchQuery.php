<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfBatchQuery
 */
class ResultOfBatchQuery extends AbstractResult
{
    /**
     * Get result values for batched queries
     * Returns an array of values. Each value corresponds to queries item.
     *
     * @return array<mixed>
     */
    public function getResults(): array
    {
        return $this->requireArray('results');
    }
}
