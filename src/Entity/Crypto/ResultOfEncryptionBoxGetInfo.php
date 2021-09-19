<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Crypto;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfEncryptionBoxGetInfo
 */
class ResultOfEncryptionBoxGetInfo extends AbstractResult
{
    /**
     * @return array<string, mixed>
     */
    public function getInfo(): array
    {
        return $this->requireArray('info');
    }
}
