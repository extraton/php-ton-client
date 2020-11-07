<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Net\QueryInterface;
use Extraton\TonClient\Entity\Net\ResultOfQueryCollection;

class Net
{
    private TonClient $tonClient;

    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function queryCollection(QueryInterface $query): ResultOfQueryCollection
    {
        return new ResultOfQueryCollection(
            $this->tonClient->request(
                'net.query_collection',
                [
                    'collection' => $query->getCollection(),
                    'filter'     => $query->getFilter(),
                    'result'     => $query->getResult(),
                    'orderBy'    => $query->getOrderBy(),
                    'limit'      => $query->getLimit(),
                ]
            )->wait()
        );
    }
}

//'net.subscribe_collection',
//[
//    'collection' => 'accounts',
//    'filter'     => null,
//    'result'     => 'last_paid',
//    'limit'      => 2,
//    'orderBy'    => [
//    'path'      => 'last_paid',
//    'direction' => 'DESC'
//]
////'timeout'    => 10,
//]
