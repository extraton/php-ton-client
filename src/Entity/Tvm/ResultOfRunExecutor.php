<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Processing\DecodedOutput;

class ResultOfRunExecutor extends AbstractResult
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
     * Get updated account state BOC. Encoded as base64
     *
     * @return string
     */
    public function getAccount(): string
    {
        return $this->requireString('account');
    }

    /**
     * Get list of output messages BOCs. Encoded as base64
     *
     * @return string[]
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
     * Get optional decoded message bodies according to the optional abi parameter
     *
     * @return DecodedOutput|null
     */
    public function getDecodedOutput(): ?DecodedOutput
    {
        return DecodedOutput::fromArray($this->requireArray('decoded'));
    }
}
