<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfAttachSignatureToMessageBody extends AbstractResult
{
    /**
     * Get result
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->requireString('body');
    }
}
