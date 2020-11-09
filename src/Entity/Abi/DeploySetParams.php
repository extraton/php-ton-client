<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Abi;

use Extraton\TonClient\Entity\ParamsInterface;

class DeploySetParams implements ParamsInterface
{
    private string $tvc;

    private ?int $workchainId;

    /** @var mixed */
    private $initialData;

    /**
     * @param string $tvc Content of TVC file encoded in base64
     * @param int|null $workchainId Target workchain for destination address. Default is 0
     * @param null $initialData List of initial values for contract's public variables
     */
    public function __construct(string $tvc, ?int $workchainId = null, $initialData = null)
    {
        $this->tvc = $tvc;
        $this->workchainId = $workchainId;
        $this->initialData = $initialData;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'tvc'          => $this->tvc,
            'workchain_id' => $this->workchainId,
            'initial_data' => $this->initialData,
        ];
    }
}
