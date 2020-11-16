<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Exception\TonException;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Net;
use Generator;

/**
 * Type ResultOfSubscribeCollection
 */
class ResultOfSubscribeCollection extends AbstractResult
{
    private Net $net;

    /**
     * @param Response $response
     * @param Net $net
     */
    public function __construct(Response $response, Net $net)
    {
        parent::__construct($response);
        $this->net = $net;
    }

    /**
     * Stop watching
     *
     * @throws TonException
     */
    public function stop(): void
    {
        $this->net->unsubscribe($this->getHandle());
    }

    /**
     * Get subscription handle. Must be closed with unsubscribe
     *
     * @return int
     */
    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }

    /**
     * Get generator for iterate Event objects
     *
     * @return Generator<Event>
     */
    public function getIterator(): Generator
    {
        $response = $this->getResponse();

        $response->setEventDataTransformer(
            static fn ($eventData) => new Event(new Response($eventData))
        );

        yield from $response;
    }
}
