<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity;

use Extraton\TonClient\Handler\Response;
use Generator;
use IteratorAggregate;
use RuntimeException;

use function array_shift;
use function is_array;
use function is_int;
use function is_string;

abstract class AbstractResult implements IteratorAggregate
{
    private Response $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param array<mixed> $data
     * @return static
     */
    public static function fromArray(array $data): self
    {
        return new static(new Response($data));
    }

    /**
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return Generator<mixed>
     */
    public function getIterator(): Generator
    {
        $generator = new Generator();

        yield $generator->throw(new RuntimeException('Result is not iterable.'));
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->response->isEventsFinished();
    }

    /**
     * @param string ...$keys
     * @return mixed
     */
    protected function requireData(string ...$keys)
    {
        $result = $this->getData(...$keys);

        if ($result === null) {
            throw new RuntimeException('Invalid path by array of keys');
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return array|mixed|null
     */
    protected function getData(string ...$keys)
    {
        $result = $this->getResponse()->getResponseData();
        while ($key = array_shift($keys)) {
            if (!is_array($result) || !isset($result[$key])) {
                return null;
            }

            $result = $result[$key];
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return array<mixed>|null
     */
    protected function getArray(string ...$keys): ?array
    {
        $result = $this->getData(...$keys);

        if (!is_array($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return array<mixed>
     */
    protected function requireArray(string ...$keys): array
    {
        $result = $this->requireData(...$keys);

        if (!is_array($result)) {
            throw new RuntimeException('It\'s not an array');
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return string
     */
    protected function requireString(string ...$keys): string
    {
        $result = $this->requireData(...$keys);

        if (!is_string($result)) {
            throw new RuntimeException('It\'s not a string');
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return int
     */
    protected function requireInt(string ...$keys): int
    {
        $result = $this->requireData(...$keys);

        if (!is_int($result)) {
            throw new RuntimeException('It\'s not an integer');
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return int
     */
    protected function requireBool(string ...$keys): bool
    {
        $result = $this->requireData(...$keys);

        if (!is_bool($result)) {
            throw new RuntimeException('It\'s not a boolean');
        }

        return $result;
    }
}
