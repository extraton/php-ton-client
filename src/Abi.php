<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiType;
use Extraton\TonClient\Entity\Abi\CallSet;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySet;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignature;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignatureToMessageBody;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeAccount;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessageBody;
use Extraton\TonClient\Entity\Abi\Signer;
use Extraton\TonClient\Entity\Abi\StateInitSource;
use Extraton\TonClient\Exception\TonException;

/**
 * Provides message encoding and decoding according to the ABI specification
 */
class Abi extends AbstractModule
{
    /**
     * Encodes message body according to ABI function call
     *
     * @param AbiType $abi Contract ABI
     * @param Signer $signer Signing parameters
     * @param CallSet $callSet Function call parameters
     * @param bool $isInternal True if internal message body must be encoded
     * @param int|null $processingTryIndex Processing try index
     * @return ResultOfEncodeMessageBody
     * @throws TonException
     */
    public function encodeMessageBody(
        AbiType $abi,
        Signer $signer,
        CallSet $callSet,
        bool $isInternal,
        ?int $processingTryIndex = null
    ): ResultOfEncodeMessageBody {
        return new ResultOfEncodeMessageBody(
            $this->tonClient->request(
                'abi.encode_message_body',
                [
                    'abi'                  => $abi,
                    'signer'               => $signer,
                    'call_set'             => $callSet,
                    'is_internal'          => $isInternal,
                    'processing_try_index' => $processingTryIndex,
                ]
            )->wait()
        );
    }

    /**
     * Decodes message body using provided body BOC and ABI
     *
     * @param AbiType $abi Contract ABI used to decode
     * @param string $body Message body BOC encoded in base64
     * @param bool $isInternal True if the body belongs to the internal message
     * @return DecodedMessageBody
     * @throws TonException
     */
    public function decodeMessageBody(AbiType $abi, string $body, bool $isInternal = false): DecodedMessageBody
    {
        return new DecodedMessageBody(
            $this->tonClient->request(
                'abi.decode_message_body',
                [
                    'abi'         => $abi,
                    'body'        => $body,
                    'is_internal' => $isInternal,
                ]
            )->wait()
        );
    }

    /**
     * Encodes an ABI-compatible message
     *
     * @param AbiType $abi Contract ABI
     * @param Signer $signer Signing parameters
     * @param DeploySet|null $deploySet Deploy parameters
     * @param CallSet|null $callSet Function call parameters
     * @param string|null $address Target address the message will be sent to
     * @param int|null $processingTryIndex Processing try index
     * @return ResultOfEncodeMessage
     * @throws TonException
     */
    public function encodeMessage(
        AbiType $abi,
        Signer $signer,
        ?DeploySet $deploySet = null,
        ?CallSet $callSet = null,
        ?string $address = null,
        ?int $processingTryIndex = null
    ): ResultOfEncodeMessage {
        return new ResultOfEncodeMessage(
            $this->tonClient->request(
                'abi.encode_message',
                [
                    'abi'                  => $abi,
                    'signer'               => $signer,
                    'deploy_set'           => $deploySet,
                    'call_set'             => $callSet,
                    'address'              => $address,
                    'processing_try_index' => $processingTryIndex,
                ]
            )->wait()
        );
    }

    /**
     * Decodes message body using provided message BOC and ABI
     *
     * @param AbiType $abi Contract ABI used to decode
     * @param string $message Boc message
     * @return DecodedMessageBody
     * @throws TonException
     */
    public function decodeMessage(AbiType $abi, string $message): DecodedMessageBody
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

    /**
     * Combines hex-encoded signature with base64-encoded unsigned_message. Returns signed message encoded in base64
     *
     * @param AbiType $abi Contract ABI
     * @param string $publicKey Public key encoded in hex
     * @param string $message Unsigned message BOC encoded in base64
     * @param string $signature Signature encoded in hex
     * @return ResultOfAttachSignature
     * @throws TonException
     */
    public function attachSignature(
        AbiType $abi,
        string $publicKey,
        string $message,
        string $signature
    ): ResultOfAttachSignature {
        return new ResultOfAttachSignature(
            $this->tonClient->request(
                'abi.attach_signature',
                [
                    'abi'        => $abi,
                    'public_key' => $publicKey,
                    'message'    => $message,
                    'signature'  => $signature,
                ]
            )->wait()
        );
    }

    /**
     * Attach signature to message body
     *
     * @param AbiType $abi Contract ABI
     * @param string $publicKey Public key. Must be encoded with hex
     * @param string $message Unsigned message BOC. Must be encoded with base64
     * @param string $signature Signature. Must be encoded with hex
     * @return ResultOfAttachSignatureToMessageBody
     * @throws TonException
     */
    public function attachSignatureToMessageBody(
        AbiType $abi,
        string $publicKey,
        string $message,
        string $signature
    ): ResultOfAttachSignatureToMessageBody {
        return new ResultOfAttachSignatureToMessageBody(
            $this->tonClient->request(
                'abi.attach_signature_to_message_body',
                [
                    'abi'        => $abi,
                    'public_key' => $publicKey,
                    'message'    => $message,
                    'signature'  => $signature,
                ]
            )->wait()
        );
    }

    /**
     * Creates account state BOC
     *
     * @param StateInitSource $stateInitSource Source of the account state init
     * @param int|null $balance Initial balance
     * @param int|null $lastTransLt Initial value for the last_trans_lt
     * @param int|null $lastPaid Initial value for the last_paid
     * @return ResultOfEncodeAccount
     * @throws TonException
     */
    public function encodeAccount(
        StateInitSource $stateInitSource,
        ?int $balance = null,
        ?int $lastTransLt = null,
        ?int $lastPaid = null
    ): ResultOfEncodeAccount {
        return new ResultOfEncodeAccount(
            $this->tonClient->request(
                'abi.encode_account',
                [
                    'state_init'    => $stateInitSource,
                    'balance'       => $balance,
                    'last_trans_lt' => $lastTransLt,
                    'last_paid'     => $lastPaid,
                ]
            )->wait()
        );
    }
}
