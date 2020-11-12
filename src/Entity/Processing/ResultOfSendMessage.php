<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfSendMessage extends AbstractResult
{
    /**
     * @return string
     */
    public function getShardBlockId(): string
    {
        return $this->requireString('shard_block_id');
    }
}
