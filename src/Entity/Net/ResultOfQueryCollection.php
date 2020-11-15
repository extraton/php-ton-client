<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfQueryCollection
 */
class ResultOfQueryCollection extends AbstractResult
{
    /**
     * @return array<mixed>
     */
    public function getResult(): array
    {
        return $this->requireArray('result');
    }
}
