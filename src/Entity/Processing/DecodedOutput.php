<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Handler\Response;

/**
 * Type DecodedOutput
 */
class DecodedOutput extends AbstractResult
{
    /**
     * Get Decoded bodies of the out messages.
     * If the message can't be decoded, then None will be stored in the appropriate position.
     *
     * @return DecodedMessageBody
     */
    public function getDecodedMessageBody(): DecodedMessageBody
    {
        return new DecodedMessageBody(new Response($this->requireArray('out_messages')));
    }

    /**
     * Get decoded body of the function output message
     *
     * @return mixed
     */
    public function getOutput()
    {
        return $this->getOriginData('output');
    }
}
