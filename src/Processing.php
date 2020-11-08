<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Processing\ResultOfSendMessage;

/**
 * Message processing module
 */
class Processing
{
    private TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function sendMessage(string $message, $abi, bool $sendEvents): ResultOfSendMessage
    {
    }
}
