<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

use Closure;
use Extraton\TonClient\Exception\LogicException;
use Generator;
use IteratorAggregate;

use function array_shift;
use function usleep;

/**
 * @phpstan-implements IteratorAggregate<mixed>
 */
class Response implements IteratorAggregate
{
    private bool $dataFetched;

    private bool $eventsFinished;

    /** @var array<mixed> */
    private array $responseData;

    /** @var array<mixed> */
    private array $eventData = [];

    private ?Closure $eventDataTransformer = null;

    /**
     * @param array<mixed> $responseData
     * @param bool $dataFetched
     * @param bool $eventsFinished
     */
    public function __construct(array $responseData = [], bool $dataFetched = true, bool $eventsFinished = true)
    {
        $this->responseData = $responseData;
        $this->dataFetched = $dataFetched;
        $this->eventsFinished = $eventsFinished;
    }

    /**
     * @param array<mixed> $responseData
     * @return self
     */
    public function setResponseData(array $responseData): self
    {
        $this->responseData = $responseData;
        $this->dataFetched = true;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getResponseData(): array
    {
        if ($this->dataFetched) {
            return $this->responseData;
        }

        // @phpstan-ignore-next-line
        while (!$this->dataFetched) {
            usleep(500_000);
        }

        // @phpstan-ignore-next-line
        return $this->responseData;
    }

    public function setEventDataTransformer(callable $eventTransformer): void
    {
        $this->eventDataTransformer = Closure::fromCallable($eventTransformer);
    }

    public function finish(): void
    {
        $this->eventsFinished = true;
    }

    public function isEventsFinished(): bool
    {
        return $this->eventsFinished;
    }

    /**
     * @param array<mixed> $eventData
     */
    public function __invoke(array $eventData): void
    {
        if ($this->eventsFinished) {
            throw new LogicException('Event data cannot be transferred to a completed Response object.');
        }

        $this->eventData[] = $eventData;
    }

    /**
     * @return Generator<mixed>
     */
    public function getIterator(): Generator
    {
        for (; ;) {
            while ($data = array_shift($this->eventData)) {
                if ($this->eventDataTransformer === null) {
                    yield $data;
                } else {
                    yield call_user_func($this->eventDataTransformer, $data);
                }
            }

            if ($this->isEventsFinished()) {
                break;
            }

            usleep(500_000);
        }
    }
}
