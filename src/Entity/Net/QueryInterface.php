<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;

interface QueryInterface
{
    public function getCollection(): string;

    public function getResult(): string;

    public function getFilters(): ?Params;

    public function getOrderBy(): ?Params;

    public function getLimit(): ?int;

    public function getTimeout(): ?int;
}
