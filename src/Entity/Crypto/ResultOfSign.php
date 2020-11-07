<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfSign extends AbstractResult
{
    public function getSigned(): string
    {
        return $this->requireString('signed');
    }

    public function getSignature(): string
    {
        return $this->requireString('signature');
    }
}
