<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\ParamsInterface;

interface QueryInterface
{
    public function getCollection(): string;

    public function getResult(): string;

    public function getFilters(): ?ParamsInterface;

    public function getOrderBy(): ?ParamsInterface;

    public function getLimit(): ?int;

    public function getTimeout(): ?int;
}
