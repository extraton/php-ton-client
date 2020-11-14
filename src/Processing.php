<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\ParamsOfEncodeMessage;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Entity\Processing\ResultOfSendMessage;
use JsonException;

/**
 * Message processing module
 */
class Processing
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
     * Sends message to the network
     *
     * @param string $message Message BOC
     * @param bool $sendEvents Flag for requesting events sending
     * @param AbiParams|null $abi Optional message ABI
     * @return ResultOfSendMessage
     * @throws JsonException
     */
    public function sendMessage(string $message, bool $sendEvents, ?AbiParams $abi = null): ResultOfSendMessage
    {
        return new ResultOfSendMessage(
            $this->tonClient->request(
                'processing.send_message',
                [
                    'message'     => $message,
                    'send_events' => $sendEvents,
                    'abi'         => $abi,
                ]
            )->wait()
        );
    }

    /**
     * Performs monitoring of the network for the result transaction of the external inbound message processing.
     *
     * @param string $message Message BOC. Encoded with base64
     * @param string $shardBlockId The last generated block id of the destination account shard before the message was sent
     * @param bool $sendEvents Flag that enables / disables intermediate events
     * @param AbiParams|null $abi Optional ABI for decoding the transaction result
     * @return ResultOfProcessMessage
     * @throws JsonException
     */
    public function waitForTransaction(
        string $message,
        string $shardBlockId,
        bool $sendEvents,
        ?AbiParams $abi = null
    ): ResultOfProcessMessage {
        return new ResultOfProcessMessage(
            $this->tonClient->request(
                'processing.wait_for_transaction',
                [
                    'message'        => $message,
                    'shard_block_id' => $shardBlockId,
                    'send_events'    => $sendEvents,
                    'abi'            => $abi,
                ]
            )->wait()
        );
    }

    /**
     * Creates message, sends it to the network and monitors its processing
     *
     * @param ParamsOfEncodeMessage $paramsOfEncodeMessage Message encode parameters
     * @param bool $sendEvents Flag for requesting events sending
     * @return ResultOfProcessMessage
     * @throws JsonException
     */
    public function processMessage(
        ParamsOfEncodeMessage $paramsOfEncodeMessage,
        bool $sendEvents
    ): ResultOfProcessMessage {
        return new ResultOfProcessMessage(
            $this->tonClient->request(
                'processing.process_message',
                [
                    'message_encode_params' => $paramsOfEncodeMessage,
                    'send_events'           => $sendEvents,
                ]
            )->wait()
        );
    }
}
