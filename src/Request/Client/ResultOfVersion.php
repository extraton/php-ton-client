<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request\Client;

use Extraton\TonClient\Request\AbstractResult;

class ResultOfVersion extends AbstractResult
{
    public function getVersion(): string
    {
        return $this->requireString('version');
    }
}
