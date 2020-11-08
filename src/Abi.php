<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignature;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignatureToMessageBody;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeAccount;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessageBody;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Abi\StateInitSource;

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
     * Encodes message body according to ABI function call
     *
     * @param AbiParams $abi Contract ABI
     * @param CallSetParams $callSet Function call parameters
     * @param SignerParams $signer Signing parameters
     * @param bool $isInternal True if internal message body must be encoded
     * @param int|null $processingTryIndex Processing try index
     * @return ResultOfEncodeMessageBody
     */
    public function encodeMessageBody(
        AbiParams $abi,
        CallSetParams $callSet,
        SignerParams $signer,
        bool $isInternal,
        ?int $processingTryIndex = null
    ): ResultOfEncodeMessageBody {
        return new ResultOfEncodeMessageBody(
            $this->tonClient->request(
                'abi.encode_message_body',
                [
                    'abi'                  => $abi,
                    'call_set'             => $callSet,
                    'signer'               => $signer,
                    'is_internal'          => $isInternal,
                    'processing_try_index' => $processingTryIndex,
                ]
            )->wait()
        );
    }

    /**
     * Attach signature to message body
     *
     * @param AbiParams $abi Contract ABI
     * @param string $publicKey Public key. Must be encoded with hex
     * @param string $message Unsigned message BOC. Must be encoded with base64
     * @param string $signature Signature. Must be encoded with hex
     * @return ResultOfAttachSignatureToMessageBody
     */
    public function attachSignatureToMessageBody(
        AbiParams $abi,
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
     * Encodes an ABI-compatible message
     *
     * @param AbiParams $abi Contract ABI
     * @param SignerParams $signer Signing parameters
     * @param string|null $address Target address the message will be sent to
     * @param DeploySetParams|null $deploySet Deploy parameters
     * @param CallSetParams|null $callSet Function call parameters
     * @param int|null $processingTryIndex Processing try index
     * @return ResultOfEncodeMessage
     */
    public function encodeMessage(
        AbiParams $abi,
        SignerParams $signer,
        ?string $address = null,
        ?DeploySetParams $deploySet = null,
        ?CallSetParams $callSet = null,
        ?int $processingTryIndex = null
    ): ResultOfEncodeMessage {
        return new ResultOfEncodeMessage(
            $this->tonClient->request(
                'abi.encode_message',
                [
                    'abi'                  => $abi,
                    'signer'               => $signer,
                    'address'              => $address,
                    'deploy_set'           => $deploySet,
                    'call_set'             => $callSet,
                    'processing_try_index' => $processingTryIndex,
                ]
            )->wait()
        );
    }

    /**
     * Combines hex-encoded signature with base64-encoded unsigned_message. Returns signed message encoded in base64
     *
     * @param AbiParams $abi Contract ABI
     * @param string $publicKey Public key encoded in hex
     * @param string $message Unsigned message BOC encoded in base64
     * @param string $signature Signature encoded in hex
     * @return ResultOfAttachSignature
     */
    public function attachSignature(
        AbiParams $abi,
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
     * Decodes message body using provided message BOC and ABI
     *
     * @param AbiParams $abi Contract ABI used to decode
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

    /**
     * Decodes message body using provided body BOC and ABI
     *
     * @param AbiParams $abi Contract ABI used to decode
     * @param string $body Message body BOC encoded in base64
     * @param bool $isInternal True if the body belongs to the internal message
     * @return DecodedMessageBody
     */
    public function decodeMessageBody(AbiParams $abi, string $body, bool $isInternal = false): DecodedMessageBody
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
     * Creates account state BOC
     *
     * @param StateInitSource $stateInit Source of the account state init
     * @param int|null $balance Initial balance
     * @param int|null $lastTransLt Initial value for the last_trans_lt
     * @param int|null $lastPaid Initial value for the last_paid
     * @return ResultOfEncodeAccount
     */
    public function encodeAccount(
        StateInitSource $stateInit,
        ?int $balance = null,
        ?int $lastTransLt = null,
        ?int $lastPaid = null
    ): ResultOfEncodeAccount {
        return new ResultOfEncodeAccount(
            $this->tonClient->request(
                'abi.encode_account',
                [
                    'state_init'    => $stateInit,
                    'balance'       => $balance,
                    'last_trans_lt' => $lastTransLt,
                    'last_paid'     => $lastPaid,
                ]
            )->wait()
        );
    }
}
