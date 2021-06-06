<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfQueryTransactionTree
 */
class ResultOfQueryTransactionTree extends AbstractResult
{
    /**
     * Get Messages
     *
     * @return array<MessageNode>
     */
    public function getMessages(): array
    {
        return MessageNode::createCollection($this->requireArray('messages'));
    }

    /**
     * Get transactions
     *
     * @return array<TransactionNode>
     */
    public function getTransactions(): array
    {
        return TransactionNode::createCollection($this->requireArray('transactions'));
    }
}
