<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfQueryCollection extends AbstractResult
{
    public function getResult(): array
    {
        return $this->requireData('result');
    }
}
