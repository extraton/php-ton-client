<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

class ParamsOfWaitForCollection extends AbstractQuery
{
    public function __construct(
        string $collection,
        array $resultFields = [],
        ?Filters $filters = null,
        ?int $timeout = null
    ) {
        parent::__construct($collection, $resultFields);
        $this->setFilters($filters);
        $this->setTimeout($timeout);
    }

    public function getOrderBy(): ?Params
    {
        throw new LogicException('Method ParamsOfWaitForCollection::getOrderBy is not implemented.');
    }

    public function getLimit(): ?int
    {
        throw new LogicException('Method ParamsOfWaitForCollection::getLimit is not implemented.');
    }
}
