<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Processing;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Tvm\TransactionFees;
use Extraton\TonClient\Handler\Response;
use Generator;

/**
 * Type ResultOfProcessMessage
 */
class ResultOfProcessMessage extends AbstractResult
{
    /**
     * Get parsed transaction
     *
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->requireData('transaction');
    }

    /**
     * Get list of output messages BOCs. Encoded as base64
     *
     * @return array<string>
     */
    public function getOutMessages(): array
    {
        return $this->requireArray('out_messages');
    }

    /**
     * Get transaction fees
     *
     * @return TransactionFees
     */
    public function getTransactionFees(): TransactionFees
    {
        return new TransactionFees(
            new Response(
                $this->requireArray('fees')
            )
        );
    }

    /**
     * Get transaction fees
     *
     * @return TransactionFees
     */
    public function getFees(): TransactionFees
    {
        return $this->getTransactionFees();
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
     * Get optional decoded message bodies according to the optional abi parameter.
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
