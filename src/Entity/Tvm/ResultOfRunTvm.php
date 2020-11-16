<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Tvm;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Processing\DecodedOutput;
use Extraton\TonClient\Handler\Response;

/**
 * Type ResultOfRunTvm
 */
class ResultOfRunTvm extends AbstractResult
{
    /**
     * Get list of output messages' BOCs. Encoded as base64
     *
     * @return array<string>
     */
    public function getOutMessages(): array
    {
        return $this->requireArray('out_messages');
    }

    /**
     * Get updated account state BOC. Encoded as base64.
     * Attention! Only data in account state is updated.
     *
     * @return string
     */
    public function getAccount(): string
    {
        return $this->requireString('account');
    }

    /**
     * Get optional decoded message bodies according to the optional abi parameter.
     *
     * @return DecodedOutput|null
     */
    public function getDecoded(): ?DecodedOutput
    {
        return $this->getDecodedOutput();
    }

    /**
     * Get optional decoded message bodies according to the optional abi parameter
     *
     * @return DecodedOutput|null
     */
    public function getDecodedOutput(): ?DecodedOutput
    {
        $result = $this->getArray('decoded');

        if ($result === null) {
            return null;
        }

        return new DecodedOutput(new Response($result));
    }
}
