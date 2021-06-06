<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity;

use Extraton\TonClient\Exception\DataException;

class AbstractData
{
    /** @var array<mixed> */
    private array $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string ...$keys
     * @return array|mixed|null
     */
    protected function getOriginData(string ...$keys)
    {
        $result = $this->data;
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
        $result = $this->getOriginData(...$keys);

        if (!is_array($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return string|null
     */
    protected function getString(string ...$keys): ?string
    {
        $result = $this->getOriginData(...$keys);

        if (!is_string($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return int|null
     */
    protected function getInt(string ...$keys): ?int
    {
        $result = $this->getOriginData(...$keys);

        if (!is_int($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return mixed
     * @throws DataException
     */
    protected function requireData(string ...$keys)
    {
        $result = $this->getOriginData(...$keys);

        if ($result === null) {
            $path = implode('.', $keys);

            throw new DataException(sprintf('Data not found by key %s.', $path));
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return array<mixed>
     * @throws DataException
     */
    protected function requireArray(string ...$keys): array
    {
        $result = $this->requireData(...$keys);

        if (!is_array($result)) {
            $path = implode('.', $keys);

            throw new DataException(sprintf('Data is corrupted by key %s.', $path));
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return string
     * @throws DataException
     */
    protected function requireString(string ...$keys): string
    {
        $result = $this->requireData(...$keys);

        if (!is_string($result)) {
            $path = implode('.', $keys);

            throw new DataException(sprintf('Data is corrupted by key %s.', $path));
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return int
     * @throws DataException
     */
    protected function requireInt(string ...$keys): int
    {
        $result = $this->requireData(...$keys);

        if (!is_int($result)) {
            $path = implode('.', $keys);

            throw new DataException(sprintf('Data is corrupted by key %s.', $path));
        }

        return $result;
    }

    /**
     * @param string ...$keys
     * @return bool
     * @throws DataException
     */
    protected function requireBool(string ...$keys): bool
    {
        $result = $this->requireData(...$keys);

        if (!is_bool($result)) {
            $path = implode('.', $keys);

            throw new DataException(sprintf('Data is corrupted by key %s.', $path));
        }

        return $result;
    }
}