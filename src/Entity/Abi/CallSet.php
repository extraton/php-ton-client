<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;

/**
 * Type CallSet
 */
class CallSet implements Params
{
    private string $functionName;

    /** @var mixed */
    private $input;

    private ?FunctionHeader $functionHeader = null;

    /**
     * @param string $functionName
     */
    public function __construct(string $functionName)
    {
        $this->functionName = $functionName;
    }

    /**
     * @param mixed $input
     * @return self
     */
    public function withInput($input = null): self
    {
        $this->setInput($input);

        return $this;
    }

    /**
     * @param string|null $pubKey
     * @param int|null $time
     * @param int|null $expire
     * @return self
     */
    public function withFunctionHeaderParams(?string $pubKey = null, ?int $time = null, ?int $expire = null): self
    {
        $this->setFunctionHeader(new FunctionHeader($pubKey, $time, $expire));

        return $this;
    }

    /**
     * @param mixed $input
     * @return self
     */
    private function setInput($input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param FunctionHeader $functionHeader
     * @return self
     */
    private function setFunctionHeader(FunctionHeader $functionHeader): self
    {
        $this->functionHeader = $functionHeader;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'function_name' => $this->functionName,
            'header'        => $this->functionHeader,
            'input'         => $this->input,
        ];
    }
}
