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

    public function getHandle(): int
    {
        return $this->requireInt('handle');
    }

    public function stop(): void
    {
        $this->net->unsubscribe($this->getHandle());
    }

    public function getIterator(): Generator
    {
        yield from $this->getResponse();
    }
}
