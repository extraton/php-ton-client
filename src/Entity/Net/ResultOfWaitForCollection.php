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
     * @return array<mixed>
     */
    public function getResult(): array
    {
        return $this->requireData('result');
    }
}
