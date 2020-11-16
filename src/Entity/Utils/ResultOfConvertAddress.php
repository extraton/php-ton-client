<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Utils;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfConvertAddress
 */
class ResultOfConvertAddress extends AbstractResult
{
    /**
     * Address in the specified format
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->requireString('address');
    }
}
