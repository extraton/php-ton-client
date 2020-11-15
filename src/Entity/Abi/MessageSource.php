<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\Params;
use RuntimeException;

class MessageSource implements Params
{
    public const TYPE_ENCODED = 'Encoded';

    public const TYPE_ENCODING_PARAMS = 'EncodingParams';

    private string $type;

    private string $message;

    private ?AbiParams $abi;

    private SignerParams $signer;

    private ?string $address;

    private ?DeploySetParams $deploySet;

    private ?CallSetParams $callSet;

    private ?int $processingTryIndex;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setAbi(?AbiParams $abi): self
    {
        $this->abi = $abi;

        return $this;
    }

    /**
     * @param string $message
     * @param AbiParams|null $abi
     * @return static
     */
    public static function fromEncoded(string $message, ?AbiParams $abi = null): self
    {
        $instance = new self(self::TYPE_ENCODED);
        $instance->setMessage($message);
        $instance->setAbi($abi);

        return $instance;
    }

    /**
     * @param AbiParams $abi
     * @param SignerParams $signer
     * @param string|null $address
     * @param DeploySetParams|null $deploySet
     * @param CallSetParams|null $callSet
     * @param int|null $processingTryIndex
     * @return static
     */
    public static function fromEncodingParams(
        AbiParams $abi,
        SignerParams $signer,
        ?DeploySetParams $deploySet = null,
        ?CallSetParams $callSet = null,
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
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param DeploySetParams|null $deploySet
     * @return $this
     */
    public function setDeploySet(?DeploySetParams $deploySet): self
    {
        $this->deploySet = $deploySet;

        return $this;
    }

    private function setCallSet(?CallSetParams $callSet): self
    {
        $this->callSet = $callSet;

        return $this;
    }

    private function setSigner(SignerParams $signer): self
    {
        $this->signer = $signer;

        return $this;
    }

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
            throw new RuntimeException('Unknown type.');
        }

        return $result;
    }
}
