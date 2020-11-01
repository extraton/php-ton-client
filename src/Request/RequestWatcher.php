<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use ArrayIterator;
use GuzzleHttp\Promise\Promise;

class RequestCollection extends ArrayIterator
{
    public function add(int $requestId, Promise $promise): void
    {
        $this[$requestId] = $promise;
    }
}
