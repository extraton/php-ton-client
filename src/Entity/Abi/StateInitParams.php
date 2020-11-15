<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;

/**
 * Type StateInitParams
 */
class StateInitParams implements Params
{
    private AbiType $abi;

    /** @var mixed */
    private $value;

    /**
     * @param AbiType $abi
     * @param mixed $value
     */
    public function __construct(AbiType $abi, $value)
    {
        $this->abi = $abi;
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'abi'   => $this->abi,
            'value' => $this->value,
        ];
    }
}
