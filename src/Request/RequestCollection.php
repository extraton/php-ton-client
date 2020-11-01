<?php

declare(strict_types=1);

namespace Extraton\TonClient\Request;

use ArrayIterator;
use GuzzleHttp\Promise\Promise;

class RequestCollection extends ArrayIterator
{
    private int $requestId = 0;

    /**
     * @param Promise $promise
     */
    public function append($promise): void
    {
        $this[++$this->requestId] = $promise;
    }
}
