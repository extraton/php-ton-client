<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Handler\Response;

class DecodedOutput extends AbstractResult
{
    /**
     * @return DecodedMessageBody
     */
    public function getDecodedMessageBody(): DecodedMessageBody
    {
        return new DecodedMessageBody(new Response($this->requireArray('out_messages')));
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->getData('output');
    }
}
