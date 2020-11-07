<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

interface QueryInterface
{
    public function getCollection(): string;

    public function getResult(): string;

    public function getFilter(): ?FilterCollectionInterface;

    public function getOrderBy(): ?OrderByCollectionInterface;

    public function getLimit(): ?int;
}
