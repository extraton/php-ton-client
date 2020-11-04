<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request\Utils;

use Extraton\TonClient\Request\AbstractResult;

class ResultOfConvertAddress extends AbstractResult
{
    public function getAddress(): string
    {
        return $this->requireString('address');
    }
}
