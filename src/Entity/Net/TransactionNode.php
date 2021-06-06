<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractData;

/**
 * Type TransactionNode
 */
class TransactionNode extends AbstractData
{
    /**
     * Create collection of TransactionNode
     *
     * @param array<array<mixed>> $list
     * @return array<TransactionNode>
     */
    public static function createCollection(array $list): array
    {
        return array_map(
            fn ($data): self => new self($data),
            $list
        );
    }

    /**
     * Get transaction id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->requireString('id');
    }

    /**
     * Get in message id
     *
     * @return string
     */
    public function getInMessage(): string
    {
        return $this->requireString('in_msg');
    }

    /**
     * Get out message ids
     *
     * @return array<mixed>
     */
    public function getOutMessages(): array
    {
        return $this->requireArray('out_msgs');
    }

    /**
     * Get account address
     *
     * @return string
     */
    public function getAccountAddress(): string
    {
        return $this->requireString('account_addr');
    }

    /**
     * Get transactions total fees
     *
     * @return string
     */
    public function getTotalFees(): string
    {
        return $this->requireString('total_fees');
    }

    /**
     * Get aborted flag
     *
     * @return bool
     */
    public function getAborted(): bool
    {
        return $this->requireBool('aborted');
    }

    /**
     * Get compute phase exit code
     *
     * @return int|null
     */
    public function getExitCode(): ?int
    {
        return $this->getInt('exit_code');
    }
}
