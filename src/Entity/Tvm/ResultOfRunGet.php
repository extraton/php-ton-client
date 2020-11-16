<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfRunGet
 */
class ResultOfRunGet extends AbstractResult
{
    /**
     * Values returned by get method on stack
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->requireData('output');
    }
}
