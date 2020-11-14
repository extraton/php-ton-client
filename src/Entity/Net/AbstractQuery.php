<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\ParamsInterface;
use RuntimeException;

use function array_filter;
use function array_map;
use function array_unique;
use function explode;
use function implode;

/**
 * ParamsOfQueryCollection
 */
abstract class AbstractQuery implements QueryInterface
{
    private string $collection;

    private array $resultFields;

    private ?Filters $filters = null;

    private ?OrderBy $orderBy = null;

    private ?int $limit = null;

    private ?int $timeout = null;

    public function __construct(string $collection, array $resultFields = [])
    {
        $this->collection = $collection;
        $this->resultFields = $resultFields;
    }

    public function addResultField(string ...$fieldNames): self
    {
        $this->resultFields = [...$this->resultFields, ...$fieldNames];

        return $this;
    }

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
     * @return $this
     */
    public function addFilter(string $field, string $operator, $value): self
    {
        if ($this->filters === null) {
            $this->filters = new Filters();
        }

        $this->filters->add($field, $operator, $value);

        return $this;
    }

    public function getCollection(): string
    {
        return $this->collection;
    }

    public function getResult(): string
    {
        $fields = array_merge(
            ...array_map(
                   static fn($resultField) => explode(' ', $resultField),
                   $this->resultFields
               )
        );

        $fields = array_filter(array_map('trim', $fields));

        array_unique($fields);

        if (empty($fields)) {
            throw new RuntimeException('Empty result fields.');
        }

        return implode(' ', $fields);
    }

    public function getFilters(): ?ParamsInterface
    {
        return $this->filters;
    }

    public function setFilters(?Filters $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getOrderBy(): ?ParamsInterface
    {
        return $this->orderBy;
    }

    public function setOrderBy(?OrderBy $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setTimeout(?int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }
}
