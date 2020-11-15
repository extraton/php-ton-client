<?php

declare(strict_types=1);

namespace Extraton\TonClient;

interface Module
{
    /**
     * @return TonClient
     */
    public function getTonClient(): TonClient;
}
