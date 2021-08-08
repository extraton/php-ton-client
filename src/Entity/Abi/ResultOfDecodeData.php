<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfDecodeData
 */
class ResultOfDecodeData extends AbstractResult
{
    /**
     * Get data Decoded data as a JSON structure.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->requireData('data');
    }
}
