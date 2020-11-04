<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use RuntimeException;

use function array_shift;
use function is_array;
use function is_int;
use function is_string;
use function var_export;

abstract class AbstractResult
{
    protected array $resultData;

    public function __construct(array $resultData = [])
    {
        $this->resultData = $resultData;
    }

    protected function getResultData(): array
    {
        return $this->resultData;
    }

    /**
     * @param string ...$keys
     * @return array
     */
    protected function requireArray(string ...$keys): array
    {
        $result = $this->getResultData();
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
        $result = $this->requireArray(...$keys);

        if (!is_string($result)) {
            throw new RuntimeException('Is not a string');
        }

        return $result;
    }

    protected function requireInt(string ...$keys): int
    {
        $result = $this->requireArray(...$keys);

        if (!is_int($result)) {
            throw new RuntimeException('Is not an integer');
        }

        return $result;
    }
}
