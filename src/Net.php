<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Request\Net\ResultOfQueryCollection;

class Net
{
    private TonClient $tonClient;

    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function queryCollection(
        string $collection,
        ?string $filter = null,
        string $result,
        $order = null,
        int $limit = null
    ): ResultOfQueryCollection {
        return new ResultOfQueryCollection(
            $this->tonClient->request(
                'net.query_collection',
                [
                    'collection' => $collection,
                    'result'     => $result,
                    'limit'      => 5,
                ]
            )->wait()
        );
    }
}
