<?php

declare(strict_types=1);

namespace Extraton\TonClient\App;

use Extraton\TonClient\TonClient;

/**
 * App interface
 */
interface AppInterface
{
    /**
     * @param TonClient $tonClient
     * @param array<mixed> $requestData
     * @param int|null $appRequestId
     */
    public function __invoke(TonClient $tonClient, array $requestData, ?int $appRequestId = null): void;
}
