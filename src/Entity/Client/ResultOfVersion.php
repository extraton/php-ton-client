<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfVersion extends AbstractResult
{
    public function getVersion(): string
    {
        return $this->requireString('version');
    }
}
