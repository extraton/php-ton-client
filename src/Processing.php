<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Processing\ResultOfProcessMessage;
use Extraton\TonClient\Entity\Processing\ResultOfSendMessage;
use Extraton\TonClient\Exception\TonException;

/**
 * Message processing module
 */
class Processing extends AbstractModule
{
    /**
     * Sends message to the network
     *
     * @param string $message Message BOC
     * @param bool $sendEvents Flag for requesting events sending
     * @param AbiType|null $abi Optional message ABI
     * @return ResultOfSendMessage
     * @throws TonException
     */
    public function sendMessage(string $message, bool $sendEvents, ?AbiType $abi = null): ResultOfSendMessage
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
     * @param AbiType|null $abi Optional ABI for decoding the transaction result
     * @return ResultOfProcessMessage
     * @throws TonException
     */
    public function waitForTransaction(
        string $message,
        string $shardBlockId,
        bool $sendEvents,
        ?AbiType $abi = null
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
     * @param AbiType $abi Contract ABI
     * @param Signer $signer Signing parameters
     * @param DeploySet|null $deploySet Deploy parameters
     * @param CallSet|null $callSet Function call parameters
     * @param string|null $address Target address the message will be sent to
     * @param int|null $processingTryIndex Processing try index
     * @param bool $sendEvents Flag for requesting events sending
     * @return ResultOfProcessMessage
     * @throws TonException
     */
    public function processMessage(
        AbiType $abi,
        Signer $signer,
        ?DeploySet $deploySet = null,
        ?CallSet $callSet = null,
        ?string $address = null,
        ?int $processingTryIndex = null,
        bool $sendEvents = false
    ): ResultOfProcessMessage {
        return new ResultOfProcessMessage(
            $this->tonClient->request(
                'processing.process_message',
                [
                    'message_encode_params' => [
                        'abi'                  => $abi,
                        'signer'               => $signer,
                        'deploy_set'           => $deploySet,
                        'call_set'             => $callSet,
                        'address'              => $address,
                        'processing_try_index' => $processingTryIndex,
                    ],
                    'send_events'           => $sendEvents,
                ]
            )->wait()
        );
    }
}
