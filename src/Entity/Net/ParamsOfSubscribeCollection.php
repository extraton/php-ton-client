<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use RuntimeException;

class ParamsOfSubscribeCollection extends AbstractQuery
{
    public function __construct(
        string $collection,
        array $resultFields = [],
        ?Filters $filters = null
    ) {
        parent::__construct($collection, $resultFields);
        $this->setFilters($filters);
    }

    public function getOrderBy(): ?Params
    {
        throw new RuntimeException('Method ParamsOfSubscribeCollection::getOrderBy is not implemented.');
    }

    public function getLimit(): ?int
    {
        throw new RuntimeException('Method ParamsOfSubscribeCollection::getLimit is not implemented.');
    }

    public function getTimeout(): ?int
    {
        throw new RuntimeException('Method ParamsOfSubscribeCollection::getTimeout is not implemented.');
    }
}
