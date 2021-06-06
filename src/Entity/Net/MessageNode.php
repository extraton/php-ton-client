<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\AbstractData;
use Extraton\TonClient\Handler\Response;

/**
 * Type MessageNode
 */
class MessageNode extends AbstractData
{
    /**
     * Create collection of MessageNode
     *
     * @param array<array<mixed>> $list
     * @return array<MessageNode>
     */
    public static function createCollection(array $list): array
    {
        return array_map(
            fn ($data): self => new self($data),
            $list
        );
    }

    /**
     * Get message id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->requireString('id');
    }

    /**
     * Source transaction id. This field is missing for an external inbound messages.
     *
     * @return string|null
     */
    public function getSrcTransactionId(): ?string
    {
        return $this->getString('src_transaction_id');
    }

    /**
     * Destination transaction id. This field is missing for an external outbound messages.
     *
     * @return string|null
     */
    public function getDstTransactionId(): ?string
    {
        return $this->getString('dst_transaction_id');
    }

    /**
     * Get source address
     *
     * @return string|null
     */
    public function getSrcAddress(): ?string
    {
        return $this->getString('src');
    }

    /**
     * Destination address
     *
     * @return string|null
     */
    public function getDstAddress(): ?string
    {
        return $this->getString('dst');
    }

    /**
     * Get transferred tokens value
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->getString('value');
    }

    /**
     * Get bounce flag
     *
     * @return bool|null
     */
    public function getBounce(): ?bool
    {
        return $this->requireBool('bounce');
    }

    /**
     * Get decoded body.
     * Library tries to decode message body using provided params.abi_registry.
     * This field will be missing if none of the provided abi can be used to decode.
     *
     * @return DecodedMessageBody|null
     */
    public function getDecodedBody(): ?DecodedMessageBody
    {
        $data = $this->getArray('decoded_body');
        if ($data === null) {
            return null;
        }

        return new DecodedMessageBody(new Response($data));
    }
}