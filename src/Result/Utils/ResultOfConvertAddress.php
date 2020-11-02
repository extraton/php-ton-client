<?php

declare(strict_types=1);

namespace Extraton\TonClient\Result\Utils;

use Extraton\TonClient\Result\AbstractResult;

class ResultOfConvertAddress extends AbstractResult
{
    public function getAddress(): string
    {
        return $this->requireString('address');
    }
}
