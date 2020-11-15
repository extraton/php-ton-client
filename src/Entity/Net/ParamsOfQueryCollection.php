<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Exception\LogicException;

class ParamsOfQueryCollection extends AbstractQuery
{
    public function __construct(
        string $collection,
        array $resultFields = [],
        ?Filters $filters = null,
        ?OrderBy $orderBy = null,
        ?int $limit = null
    ) {
        parent::__construct($collection, $resultFields);
        $this->setFilters($filters);
        $this->setOrderBy($orderBy);
        $this->setLimit($limit);
    }

    public function getTimeout(): ?int
    {
        throw new LogicException('Method ParamsOfQueryCollection::getTimeout is not implemented.');
    }
}
