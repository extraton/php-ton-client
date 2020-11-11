<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Closure;
use Generator;
use IteratorAggregate;
use LogicException;

use function array_shift;

/**
 * @implements IteratorAggregate<mixed>
 */
class Response implements IteratorAggregate
{
    /** @var array<mixed> */
    private array $responseData;

    /** @var array<mixed> */
    private array $eventData = [];

    private bool $finished;

    private ?Closure $eventDataTransformer = null;

    /**
     * @param array<mixed> $responseData
     * @param bool $finished
     */
    public function __construct(array $responseData, bool $finished = true)
    {
        $this->responseData = $responseData;
        $this->finished = $finished;
    }

    /**
     * @return array<mixed>
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function setEventDataTransformer(callable $eventTransformer): void
    {
        $this->eventDataTransformer = Closure::fromCallable($eventTransformer);
    }

    public function finish(): void
    {
        $this->finished = true;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @param array<mixed> $eventData
     */
    public function __invoke(array $eventData): void
    {
        if ($this->finished) {
            throw new LogicException('Completed response.');
        }

        $this->eventData[] = $eventData;
    }

    /**
     * @return Generator<mixed>
     */
    public function getIterator(): Generator
    {
        if ($this->isFinished()) {
            throw new LogicException('Completed response.');
        }

        $sleeper = new SmartSleeper();

        for (; ;) {
            while ($data = array_shift($this->eventData)) {
                $sleeper->reset();

                if ($this->eventDataTransformer === null) {
                    yield $data;
                } else {
                    yield call_user_func($this->eventDataTransformer, $data);
                }
            }

            if ($this->isFinished()) {
                break;
            }

            $sleeper->sleep();
            $sleeper->increase();
        }
    }
}
