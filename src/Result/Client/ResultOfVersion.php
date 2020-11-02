<?php

declare(strict_types=1);

namespace Extraton\TonClient\Result\Client;

use Extraton\TonClient\Result\AbstractResult;

class ResultOfVersion extends AbstractResult
{
    public function getVersion(): string
    {
        return $this->requireString('version');
    }
}
