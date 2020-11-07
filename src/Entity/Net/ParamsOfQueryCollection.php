<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use function implode;

class ParamsOfQueryCollection implements QueryInterface
{
    private string $collection;

    private array $resultFields = [];

    private ?OrderByCollectionInterface $orderBy = null;

    private ?int $limit = null;

    public function __construct(string $collection)
    {
        $this->collection = $collection;
    }

    public function addResultField(string $fieldName): self
    {
        $this->resultFields[] = $fieldName;

        return $this;
    }

    public function addOrderBy(string $path, string $direction): self
    {
        if ($this->orderBy === null) {
            $this->orderBy = new OrderByCollection();
        }

        $this->orderBy->add($path, $direction);

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function getResult(): string
    {
        return implode(' ', array_map('trim', $this->resultFields));
    }

    public function getFilter(): ?FilterCollectionInterface
    {
        // TODO: Implement getFilter() method.
    }

    public function getOrderBy(): ?OrderByCollectionInterface
    {
        return $this->orderBy;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
