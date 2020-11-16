<?php

declare(strict_types=1);

namespace Extraton\TonClient\Exception;

use RuntimeException;

/**
 * Default TON SDK exception
 */
class SDKException extends RuntimeException implements TonException
{
    /** @var array<mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $result
     * @return self
     */
    public static function create(array $result): self
    {
        $exception = new self(
            $result['message'] ?? 'Unknown TON SDK error',
            $result['code'] ?? 0
        );
        $exception->data = $result['data'] ?? [];

        return $exception;
    }

    /**
     * Get extra data
     *
     * @return array<mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
