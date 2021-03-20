<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

use function array_filter;
use function array_map;
use function array_unique;
use function explode;
use function implode;

/**
 * Abstract query
 */
abstract class AbstractQuery implements QueryInterface
{
    private string $collection;

    /** @var array<string> */
    private array $resultFields;

    private ?Filters $filters = null;

    private ?OrderBy $orderBy = null;

    private ?int $limit = null;

    private ?int $timeout = null;

    private ?Aggregation $aggregation = null;

    /**
     * @param string $collection
     * @param array<string> $resultFields
     */
    public function __construct(string $collection, array $resultFields = [])
    {
        $this->collection = $collection;
        $this->resultFields = $resultFields;
    }

    /**
     * @param string ...$fieldNames
     * @return self
     */
    public function addResultField(string ...$fieldNames): self
    {
        $this->resultFields = [...$this->resultFields, ...$fieldNames];

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     * @return self
     */
    public function addOrderBy(string $field, string $direction): self
    {
        if ($this->orderBy === null) {
            $this->orderBy = new OrderBy();
        }

        $this->orderBy->add($field, $direction);

        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @return self
     */
    public function addFilter(string $field, string $operator, $value): self
    {
        if ($this->filters === null) {
            $this->filters = new Filters();
        }

        $this->filters->add($field, $operator, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @inheritDoc
     */
    public function getResult(): string
    {
        $fields = array_merge(
            ...array_map(
                static fn ($resultField): array => explode(' ', $resultField),
                $this->resultFields
            )
        );

        $fields = array_unique(array_filter(array_map('trim', $fields)));

        if (empty($fields)) {
            throw new LogicException('Result fields cannot be empty');
        }

        return implode(' ', $fields);
    }

    /**
     * @inheritDoc
     */
    public function getFilters(): ?Params
    {
        return $this->filters;
    }

    /**
     * @param Filters|null $filters
     * @return self
     */
    public function setFilters(?Filters $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): ?Params
    {
        return $this->orderBy;
    }

    /**
     * @param OrderBy|null $orderBy
     * @return self
     */
    public function setOrderBy(?OrderBy $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return self
     */
    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    /**
     * @param int|null $timeout
     * @return self
     */
    public function setTimeout(?int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAggregation(): ?Aggregation
    {
        return $this->aggregation;
    }

    /**
     * @param Aggregation|null $aggregation
     */
    public function setAggregation(?Aggregation $aggregation): void
    {
        $this->aggregation = $aggregation;
    }
}
