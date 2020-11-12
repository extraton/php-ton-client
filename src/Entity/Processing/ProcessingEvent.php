<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;

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
}
