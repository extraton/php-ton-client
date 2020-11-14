<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;

/**
 * ParamsOfEncodeMessage
 */
class ParamsOfEncodeMessage implements ParamsInterface
{
    private AbiParams $abi;

    private SignerParams $signer;

    private ?DeploySetParams $deploySet;

    private ?CallSetParams $callSet;

    private ?string $address;

    private ?int $processingTryIndex;

    /**
     * @param AbiParams $abi Contract ABI
     * @param SignerParams $signer Signing parameters
     * @param DeploySetParams|null $deploySet Deploy parameters
     * @param CallSetParams|null $callSet Function call parameters
     * @param string|null $address Target address the message will be sent to
     * @param int|null $processingTryIndex Processing try index
     */
    public function __construct(
        AbiParams $abi,
        SignerParams $signer,
        ?DeploySetParams $deploySet = null,
        ?CallSetParams $callSet = null,
        ?string $address = null,
        ?int $processingTryIndex = null
    ) {
        $this->abi = $abi;
        $this->signer = $signer;
        $this->deploySet = $deploySet;
        $this->callSet = $callSet;
        $this->address = $address;
        $this->processingTryIndex = $processingTryIndex;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'abi'                  => $this->abi,
            'signer'               => $this->signer,
            'deploy_set'           => $this->deploySet,
            'call_set'             => $this->callSet,
            'address'              => $this->address,
            'processing_try_index' => $this->processingTryIndex,
        ];
    }
}
