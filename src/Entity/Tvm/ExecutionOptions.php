<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\ParamsInterface;

/**
 * Execution options
 */
class ExecutionOptions implements ParamsInterface
{
    private ?string $blockchainConfig;

    private ?int $blockTime;

    private ?int $blockLt;

    private ?int $transactionLt;

    /**
     * @param string|null $blockchainConfig Boc with config
     * @param int|null $blockTime Time that is used as transaction time
     * @param int|null $blockLt Block logical time
     * @param int|null $transactionLt Transaction logical time
     */
    public function __construct(?string $blockchainConfig, ?int $blockTime, ?int $blockLt, ?int $transactionLt)
    {
        $this->blockchainConfig = $blockchainConfig;
        $this->blockTime = $blockTime;
        $this->blockLt = $blockLt;
        $this->transactionLt = $transactionLt;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'blockchain_config' => $this->blockchainConfig,
            'block_time'        => $this->blockTime,
            'block_lt'          => $this->blockLt,
            'transaction_lt'    => $this->transactionLt,
        ];
    }
}
