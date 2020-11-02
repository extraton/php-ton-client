<?php

declare(strict_types=1);

namespace Extraton\TonClient\Exception;

use RuntimeException;

class RequestException extends RuntimeException implements TonException
{
    private array $data;

    public static function create(array $result): self
    {
        $exception = new self(
            $result['message'] ?? 'Unknown request error',
            $result['code'] ?? 0
        );
        $exception->data = $result['data'] ?? [];

        return $exception;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
