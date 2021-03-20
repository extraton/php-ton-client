<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfAggregateCollection
 */
class ResultOfAggregateCollection extends AbstractResult
{
    /**
     * Get values for requested fields
     * Returns an array of strings. Each string refers to the corresponding fields item
     * Numeric value is returned as a decimal string representations
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->getOriginData('values');
    }
}
