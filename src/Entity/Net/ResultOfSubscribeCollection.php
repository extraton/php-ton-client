<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Net;
use Generator;

class ResultOfSubscribeCollection extends AbstractResult
{
    private Net $net;

    public function __construct(Response $response, Net $net)
    {
        parent::__construct($response);
        $this->net = $net;
    }

    public function getResult(): array
    {
        return $this->requireData('result');
    }

    public function stop(): void
    {
        $this->net->unsubscribe($this->getHandle());
    }

    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }

    /**
     * @return Generator<Event>
     */
    public function getIterator(): Generator
    {
        $response = $this->getResponse();

        $response->setEventDataTransformer(
            static fn($eventData) => new Event(new Response($eventData))
        );

        yield from $response;
    }
}
