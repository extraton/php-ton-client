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

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param string ...$keys
     * @return mixed
     */
    protected function requireData(string ...$keys)
    {
        $result = $this->getResponse()->getResponseData();
        while ($key = array_shift($keys)) {
            if (!is_array($result) || !isset($result[$key])) {
                throw new RuntimeException('Invalid path by array of keys');
            }

            $result = $result[$key];
        }

        return $result;
    }

    protected function requireString(string ...$keys): string
    {
        $result = $this->requireData(...$keys);

        if (!is_string($result)) {
            throw new RuntimeException('Is not a string');
        }

        return $result;
    }

    protected function requireInt(string ...$keys): int
    {
        $result = $this->requireData(...$keys);

        if (!is_int($result)) {
            throw new RuntimeException('Is not an integer');
        }

        return $result;
    }

    public function getIterator(): Generator
    {
        yield from $this->getResponse()->getIterator();
    }
}
