<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

/**
 * Type ParamsOfAggregateCollection
 */
class ParamsOfAggregateCollection extends AbstractQuery
{
    /**
     * @param string $collection
     * @param Filters|null $filters
     * @param Aggregation|null $aggregation
     */
    public function __construct(
        string $collection,
        ?Filters $filters = null,
        Aggregation $aggregation = null
    ) {
        parent::__construct($collection, []);
        $this->setFilters($filters);
        $this->setAggregation($aggregation);
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        throw new LogicException('Method ParamsOfAggregateCollection::getResult is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): ?Params
    {
        throw new LogicException('Method ParamsOfAggregateCollection::getOrderBy is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        throw new LogicException('Method ParamsOfAggregateCollection::getLimit is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): ?int
    {
        throw new LogicException('Method ParamsOfAggregateCollection::getTimeout is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'collection' => $this->getCollection(),
            'filter'     => $this->getFilters(),
            'fields'     => $this->getAggregation(),
        ];
    }
}
