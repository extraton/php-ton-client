<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;

/**
 * Provides message encoding and decoding according to the ABI specification
 */
class Abi
{
    private TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    /**
     * Decodes message body using provided message BOC and ABI
     *
     * @param AbiParams $abi Abi parameters
     * @param string $message Boc message
     * @return DecodedMessageBody
     */
    public function decodeMessage(AbiParams $abi, string $message): DecodedMessageBody
    {
        return new DecodedMessageBody(
            $this->tonClient->request(
                'abi.decode_message',
                [
                    'abi'     => $abi,
                    'message' => $message,
                ]
            )->wait()
        );
    }

    public function decodeMessageFromJson(string $abiJson, string $message): DecodedMessageBody
    {
        return $this->decodeMessage(
            AbiParams::fromJson($abiJson),
            $message
        );
    }

    public function decodeMessageFromArray(array $abiArray, string $message): DecodedMessageBody
    {
        return $this->decodeMessage(
            AbiParams::fromArray($abiArray),
            $message
        );
    }

    public function decodeMessageFromHandle(int $handle, string $message): DecodedMessageBody
    {
        return $this->decodeMessage(
            AbiParams::fromHandle($handle),
            $message
        );
    }
}
