<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type DecodedMessageBody
 */
class DecodedMessageBody extends AbstractResult
{
    /**
     * Get type of the message body content
     *
     * @return string
     */
    public function getBodyType(): string
    {
        return $this->requireString('body_type');
    }

    /**
     * Get function or event name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->requireString('name');
    }

    /**
     * Get parameters or result value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->getData('value');
    }

    /**
     * Get function header
     *
     * @return FunctionHeader|null
     */
    public function getFunctionHeader(): ?FunctionHeader
    {
        $result = $this->getArray('header');
        if ($result === null) {
            return null;
        }

        return new FunctionHeader();
    }
}
