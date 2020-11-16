<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type TransactionFees
 */
class TransactionFees extends AbstractResult
{
    /**
     * @return int
     */
    public function getInMsgFwdFee(): int
    {
        return $this->requireInt('in_msg_fwd_fee');
    }

    /**
     * @return int
     */
    public function getStorageFee(): int
    {
        return $this->requireInt('storage_fee');
    }

    /**
     * @return int
     */
    public function getGasFee(): int
    {
        return $this->requireInt('gas_fee');
    }

    /**
     * @return int
     */
    public function getOutMsgsFwdFee(): int
    {
        return $this->requireInt('out_msgs_fwd_fee');
    }

    /**
     * @return int
     */
    public function getTotalAccountFees(): int
    {
        return $this->requireInt('total_account_fees');
    }

    /**
     * @return int
     */
    public function getTotalOutput(): int
    {
        return $this->requireInt('total_output');
    }
}
