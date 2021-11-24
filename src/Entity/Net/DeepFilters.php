<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Net\Filters;
use Extraton\TonClient\Exception\LogicException;

class DeepFilters extends Filters
{
    public function addDeep(array $fields, string $operator, $value): self
    {
        $field = reset($fields);

        if (isset($this->filters[$field])) {
            throw new LogicException(sprintf('Field %s already defined.', $field));
        }

        $fieldChain = [$operator => $value];
        foreach (array_reverse($fields) as $newField) {
            $fieldChain = [
                $newField => $fieldChain
            ];
        }

        $this->filters[$field] = $fieldChain[$field];

        return $this;
    }
}