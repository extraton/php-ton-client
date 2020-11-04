<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request\Net;

use Extraton\TonClient\Request\AbstractResult;

class ResultOfQueryCollection extends AbstractResult
{
    public function getResult(): array
    {
        return $this->requireArray('result');
    }
}
