<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

/**
 * Type ParamsOfSubscribeCollection
 */
class ParamsOfSubscribeCollection extends AbstractQuery
{
    /**
     * @param string $collection
     * @param array<string> $resultFields
     * @param Filters|null $filters
     */
    public function __construct(
        string $collection,
        array $resultFields = [],
        ?Filters $filters = null
    ) {
        parent::__construct($collection, $resultFields);
        $this->setFilters($filters);
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): ?Params
    {
        throw new LogicException('Method ParamsOfSubscribeCollection::getOrderBy is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        throw new LogicException('Method ParamsOfSubscribeCollection::getLimit is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): ?int
    {
        throw new LogicException('Method ParamsOfSubscribeCollection::getTimeout is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getAggregation(): ?Aggregation
    {
        throw new LogicException('Method ParamsOfSubscribeCollection::getAggregation is not implemented.');
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
        ];
    }
}
