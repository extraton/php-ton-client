<?php

declare(strict_types=1);

namespace Extraton\TonClient;

abstract class AbstractModule implements Module
{
    protected TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    /**
     * @return TonClient
     */
    public function getTonClient(): TonClient
    {
        return $this->tonClient;
    }
}
