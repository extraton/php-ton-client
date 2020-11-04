<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use RuntimeException;

use function array_shift;
use function is_array;
use function is_int;
use function is_string;

abstract class AbstractResult
{
    protected array $result;

    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    protected function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param string ...$keys
     * @return mixed
     */
    protected function requireData(string ...$keys)
    {
        $result = $this->getResult();
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
}
