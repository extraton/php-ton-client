<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

/**
 * Type ParamsOfWaitForCollection
 */
class ParamsOfWaitForCollection extends AbstractQuery
{
    /**
     * @param string $collection
     * @param array<string> $resultFields
     * @param Filters|null $filters
     * @param int|null $timeout
     */
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

    /**
     * @inheritDoc
     */
    public function getOrderBy(): ?Params
    {
        throw new LogicException('Method ParamsOfWaitForCollection::getOrderBy is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        throw new LogicException('Method ParamsOfWaitForCollection::getLimit is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getAggregation(): ?Aggregation
    {
        throw new LogicException('Method ParamsOfWaitForCollection::getAggregation is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'collection' => $this->getCollection(),
            'result'     => $this->getResult(),
            'filter'     => $this->getFilters(),
            'timeout'    => $this->getTimeout(),
        ];
    }
}
