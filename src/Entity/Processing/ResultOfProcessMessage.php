<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Tvm\TransactionFees;

/**
 * Result of call method processing.wait_for_transaction
 */
class ResultOfProcessMessage extends AbstractResult
{
    /**
     * Get parsed transaction
     *
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->requireData('transaction');
    }

    /**
     * Get list of output messages BOCs. Encoded as base64
     *
     * @return array<string>
     */
    public function getOutMessages(): array
    {
        return $this->requireArray('out_messages');
    }

    /**
     * Get transaction fees
     *
     * @return TransactionFees
     */
    public function getTransactionFees(): TransactionFees
    {
        return TransactionFees::fromArray($this->requireArray('fees'));
    }

    /**
     * Get transaction fees
     *
     * @return TransactionFees
     */
    public function getFees(): TransactionFees
    {
        return $this->getTransactionFees();
    }

    /**
     * Get optional decoded message bodies according to the optional abi parameter.
     *
     * @return DecodedOutput|null
     */
    public function getDecoded(): ?DecodedOutput
    {
        return DecodedOutput::fromArray($this->requireArray('decoded'));
    }
}
