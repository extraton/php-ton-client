<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfWaitForCollection
 */
class ResultOfWaitForCollection extends AbstractResult
{
    /**
     * Get first found object that matches the provided criteria
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->getOriginData('result');
    }
}
