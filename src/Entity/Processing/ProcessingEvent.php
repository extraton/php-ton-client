<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Client\ClientError;
use Extraton\TonClient\Handler\Response;

/**
 * Type ProcessingEvent
 */
class ProcessingEvent extends AbstractResult
{
    public const TYPE_WILL_FETCH_FIRST_BLOCK = 'WillFetchFirstBlock';

    public const TYPE_FETCH_FIRST_BLOCK_FAILED = 'FetchFirstBlockFailed';

    public const TYPE_WILL_SEND = 'WillSend';

    public const TYPE_DID_SEND = 'DidSend';

    public const TYPE_SEND_FAILED = 'SendFailed';

    public const TYPE_WILL_FETCH_NEXT_BLOCK = 'WillFetchNextBlock';

    public const TYPE_FETCH_NEXT_BLOCK_FAILED = 'FetchNextBlockFailed';

    public const TYPE_MESSAGE_EXPIRED = 'MessageExpired';

    /**
     * Get event type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->requireString('type');
    }

    /**
     * Get client error
     *
     * @return ClientError
     */
    public function getError(): ClientError
    {
        return new ClientError(new Response($this->requireArray('error')));
    }

    /**
     * Get shard block ID
     *
     * @return string
     */
    public function getShardBlockId(): string
    {
        return $this->requireString('shard_block_id');
    }

    /**
     * Get message ID
     *
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->requireString('message_id');
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->requireString('message');
    }
}
