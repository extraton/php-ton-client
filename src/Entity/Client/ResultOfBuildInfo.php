<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfBuildInfo
 */
class ResultOfBuildInfo extends AbstractResult
{
    public function getBuildNumber(): int
    {
        return $this->requireInt('build_info', 'build_number');
    }

    /**
     * @return array<mixed>
     */
    public function getDependencies(): array
    {
        return $this->requireArray('build_info', 'dependencies');
    }
}
