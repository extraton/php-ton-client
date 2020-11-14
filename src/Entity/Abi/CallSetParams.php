<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;

class CallSetParams implements ParamsInterface
{
    private string $functionName;

    private ?FunctionHeaderParams $header;

    /** @var mixed */
    private $input;

    /**
     * @param string $functionName
     * @param FunctionHeaderParams|null $header
     * @param mixed $input
     */
    public function __construct(string $functionName, ?FunctionHeaderParams $header = null, $input = null)
    {
        $this->functionName = $functionName;
        $this->header = $header;
        $this->input = $input;
    }

    public function jsonSerialize(): array
    {
        return [
            'function_name' => $this->functionName,
            'header'        => $this->header,
            'input'         => $this->input,
        ];
    }
}
