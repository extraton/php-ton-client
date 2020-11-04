<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request\Boc;

use Extraton\TonClient\Request\AbstractResult;

class ResultOfParse extends AbstractResult
{
    public function getParsed(): array
    {
        return $this->requireArray('parsed');
    }
}
