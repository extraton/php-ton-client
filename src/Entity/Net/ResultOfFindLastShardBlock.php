<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfFindLastShardBlock
 */
class ResultOfFindLastShardBlock extends AbstractResult
{
    /**
     * Get account shard last block ID
     *
     * @return string
     */
    public function getBlockId(): string
    {
        return $this->requireString('block_id');
    }
}
