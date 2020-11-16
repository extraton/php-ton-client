<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfVersion
 */
class ResultOfVersion extends AbstractResult
{
    /**
     * Get SDK version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->requireString('version');
    }
}
