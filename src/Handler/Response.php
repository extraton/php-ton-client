<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Generator;
use IteratorAggregate;
use LogicException;

use function array_shift;

class Response implements IteratorAggregate
{
    private array $responseData;

    private array $eventData = [];

    private bool $finished;

    public function __construct(array $responseData, bool $finished = true)
    {
        $this->responseData = $responseData;
        $this->finished = $finished;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function __invoke(array $eventData): void
    {
        if ($this->finished) {
            throw new LogicException('Completed response.');
        }

        $this->eventData[] = $eventData;
    }

    /**
     * @return Generator<array<mixed>>
     */
    public function getIterator(): Generator
    {
        if ($this->finished) {
            throw new LogicException('Completed response.');
        }

        return (function () {
            $sleeper = new SmartSleeper();

            for (; ;) {
                while ($data = array_shift($this->eventData)) {
                    $sleeper->reset();

                    yield $data;
                }

                $sleeper->sleep();
                $sleeper->increase();
            }
        })();
    }
}
