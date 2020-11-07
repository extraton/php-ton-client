<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Boc;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfParse extends AbstractResult
{
    public function getParsed(): array
    {
        return $this->requireData('parsed');
    }
}
