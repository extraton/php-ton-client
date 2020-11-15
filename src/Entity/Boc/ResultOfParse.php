<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfParse
 */
class ResultOfParse extends AbstractResult
{
    /**
     * @return array<mixed>
     */
    public function getParsed(): array
    {
        return $this->requireArray('parsed');
    }
}
