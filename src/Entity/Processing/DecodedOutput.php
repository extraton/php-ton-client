<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\AbstractResult;

class DecodedOutput extends AbstractResult
{
    /**
     * @return DecodedMessageBody
     */
    public function getDecodedMessageBody(): DecodedMessageBody
    {
        // @todo
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->requireData('output');
    }
}
