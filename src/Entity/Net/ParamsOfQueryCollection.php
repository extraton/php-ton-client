<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Exception\LogicException;

/**
 * Type ParamsOfQueryCollection
 */
class ParamsOfQueryCollection extends AbstractQuery
{
    /**
     * @param string $collection
     * @param array<string> $resultFields
     * @param Filters|null $filters
     * @param OrderBy|null $orderBy
     * @param int|null $limit
     */
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

    /**
     * @inheritDoc
     */
    public function getTimeout(): ?int
    {
        throw new LogicException('Method ParamsOfQueryCollection::getTimeout is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function getAggregation(): ?Aggregation
    {
        throw new LogicException('Method ParamsOfQueryCollection::getAggregation is not implemented.');
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
            'order'      => $this->getOrderBy(),
            'limit'      => $this->getLimit(),
        ];
    }

    /**
     * @param string[] $fieldNames
     * @return $this
     */
    public function addDeepResultField(string ...$fieldNames): self
    {
        $this->addResultField($this->getRecursiveDeepResultField($fieldNames, 0));
        return $this;
    }

    /**
     * @param string[] $fieldNames
     * @param int $index
     * @return string
     */
    private function getRecursiveDeepResultField(array $fieldNames, int $index): string
    {
        if ($index >= count($fieldNames)) {
            return '';
        }
        $result = $fieldNames[$index];
        $nextPart = $this->getRecursiveDeepResultField($fieldNames, $index + 1);
        $nextPart = $nextPart ? "{ {$nextPart} }" : '';
        return "{$result} {$nextPart}";
    }
}
