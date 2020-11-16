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
     * @param string $functionName Function name that is being called
     */
    public function __construct(string $functionName)
    {
        $this->functionName = $functionName;
    }

    /**
     * Set function input parameters
     *
     * @param mixed $input Function input parameters according to ABI
     * @return self
     */
    public function withInput($input = null): self
    {
        $this->setInput($input);

        return $this;
    }

    /**
     * Set function input parameters
     *
     * @param mixed $input Function input parameters according to ABI
     * @return self
     */
    private function setInput($input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Set FunctionHeader by params
     *
     * @param string|null $pubKey Public key used to sign message. Encoded with hex
     * @param int|null $time Message creation time in milliseconds
     * @param int|null $expire Message expiration time in seconds
     * @return self
     */
    public function withFunctionHeaderParams(?string $pubKey = null, ?int $time = null, ?int $expire = null): self
    {
        $this->setFunctionHeader(new FunctionHeader($pubKey, $time, $expire));

        return $this;
    }

    /**
     * Set FunctionHeader
     *
     * @param FunctionHeader $functionHeader Function header. If an application omits some header parameters required
     *                                       by the contract's ABI, the library will set the default values forthem.
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
