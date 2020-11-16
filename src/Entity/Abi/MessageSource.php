<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\DataException;

use function sprintf;

/**
 * Type MessageSource
 */
class MessageSource implements Params
{
    public const TYPE_ENCODED = 'Encoded';

    public const TYPE_ENCODING_PARAMS = 'EncodingParams';

    private string $type;

    private string $message;

    private ?AbiType $abi;

    private Signer $signer;

    private ?string $address;

    private ?DeploySet $deploySet;

    private ?CallSet $callSet;

    private ?int $processingTryIndex;

    /**
     * @param string $type Type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create MessageSource from encoded
     *
     * @param string $message Message
     * @param AbiType|null $abi Contract ABI
     * @return self
     */
    public static function fromEncoded(string $message, ?AbiType $abi = null): self
    {
        $instance = new self(self::TYPE_ENCODED);
        $instance->setMessage($message);
        $instance->setAbi($abi);

        return $instance;
    }

    /**
     * Create MessageSource from encoding params
     *
     * @param AbiType $abi Contract ABI
     * @param Signer $signer Signing parameters
     * @param string|null $address Target address the message will be sent to
     * @param DeploySet|null $deploySet Deploy parameters. Must be specified in case of deploy message
     * @param CallSet|null $callSet Function call parameters. Must be specified in case of non-deploy message
     * @param int|null $processingTryIndex Processing try index
     * @return self
     */
    public static function fromEncodingParams(
        AbiType $abi,
        Signer $signer,
        ?DeploySet $deploySet = null,
        ?CallSet $callSet = null,
        ?string $address = null,
        ?int $processingTryIndex = null
    ): self {
        $instance = new self(self::TYPE_ENCODING_PARAMS);
        $instance->setAbi($abi);
        $instance->setSigner($signer);
        $instance->setAddress($address);
        $instance->setDeploySet($deploySet);
        $instance->setCallSet($callSet);
        $instance->setProcessingTryIndex($processingTryIndex);

        return $instance;
    }

    /**
     * Set message
     *
     * @param string $message Message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set contract ABI
     *
     * @param AbiType|null $abi Contract ABI
     * @return self
     */
    public function setAbi(?AbiType $abi): self
    {
        $this->abi = $abi;

        return $this;
    }

    /**
     * Set signing parameters
     *
     * @param Signer $signer Signing parameters
     * @return self
     */
    private function setSigner(Signer $signer): self
    {
        $this->signer = $signer;

        return $this;
    }

    /**
     * Set target address the message will be sent to. Must be specified in case of non-deploy message.
     *
     * @param string|null $address Target address the message will be sent to
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Set deploy parameters. Must be specified in case of deploy message.
     *
     * @param DeploySet|null $deploySet Deploy parameters
     * @return self
     */
    public function setDeploySet(?DeploySet $deploySet): self
    {
        $this->deploySet = $deploySet;

        return $this;
    }

    /**
     * Set function call parameters. Must be specified in case of non-deploy message.
     *
     * @param CallSet|null $callSet Function call parameters
     * @return self
     */
    private function setCallSet(?CallSet $callSet): self
    {
        $this->callSet = $callSet;

        return $this;
    }

    /**
     * Set processing try index. Used in message processing with retries (if contract's ABI includes "expire" header).
     *
     * @param int|null $processingTryIndex Processing try index
     * @return self
     */
    private function setProcessingTryIndex(?int $processingTryIndex): self
    {
        $this->processingTryIndex = $processingTryIndex;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $result['type'] = $this->type;

        if ($this->type === self::TYPE_ENCODED) {
            $result['message'] = $this->message;
            $result['abi'] = $this->abi;
        } elseif ($this->type === self::TYPE_ENCODING_PARAMS) {
            $result['abi'] = $this->abi;
            $result['signer'] = $this->signer;
            $result['address'] = $this->address;
            $result['deploy_set'] = $this->deploySet;
            $result['call_set'] = $this->callSet;
            $result['processing_try_index'] = $this->processingTryIndex;
        } else {
            throw new DataException(sprintf('Unknown type %s.', $this->type));
        }

        return $result;
    }
}
