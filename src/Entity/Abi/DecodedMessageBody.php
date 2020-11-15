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
     * Type of the message body content
     *
     * @return string
     */
    public function getBodyType(): string
    {
        return $this->requireString('body_type');
    }

    /**
     * Function or event name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->requireString('name');
    }

    /**
     * Parameters or result value
     *
     * @return array<mixed>|null
     */
    public function getValue(): ?array
    {
        return $this->requireData('value');
    }

    /**
     * Function header
     *
     * @return array<mixed>
     */
    public function getHeader(): array
    {
        return $this->requireArray('header');
    }
}
