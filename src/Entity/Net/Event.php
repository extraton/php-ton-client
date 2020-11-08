<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

class Event extends AbstractResult
{
    public function getResult(): array
    {
        return $this->requireArray('result');
    }
}
