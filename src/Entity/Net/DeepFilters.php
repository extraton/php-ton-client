<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

class DeepFilters extends Filters
{
    public function addDeep(array $fields, string $operator, $value): self
    {
        $field = reset($fields);

        $fieldChain = [$operator => $value];
        foreach (array_reverse($fields) as $newField) {
            $fieldChain = [
                $newField => $fieldChain
            ];
        }

        $this->filters[$field] =
            isset($this->filters[$field])
                ? array_merge_recursive($this->filters[$field], $fieldChain[$field])
                : $fieldChain[$field];

        return $this;
    }
}