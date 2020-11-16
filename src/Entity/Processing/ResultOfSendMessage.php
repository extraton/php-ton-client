<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Handler\Response;
use Generator;

/**
 * Type ResultOfSendMessage
 */
class ResultOfSendMessage extends AbstractResult
{
    /**
     * @return string
     */
    public function getShardBlockId(): string
    {
        return $this->requireString('shard_block_id');
    }

    /**
     * Get generator for iterate ProcessingEvent objects
     *
     * @return Generator<ProcessingEvent>
     */
    public function getIterator(): Generator
    {
        $response = $this->getResponse();

        $response->setEventDataTransformer(
            static fn ($eventData) => new ProcessingEvent(new Response($eventData))
        );

        yield from $response;
    }
}
