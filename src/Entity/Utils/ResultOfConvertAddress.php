<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfConvertAddress extends AbstractResult
{
    public function getAddress(): string
    {
        return $this->requireString('address');
    }
}
