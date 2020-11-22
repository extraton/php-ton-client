<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

/**
 * Type ResultOfBuildInfo
 */
class ResultOfBuildInfo extends AbstractResult
{
    /**
     * Get build number
     *
     * @return int
     */
    public function getBuildNumber(): int
    {
        return $this->requireInt('build_number');
    }

    /**
     * Get dependencies
     *
     * @return array<mixed>
     */
    public function getDependencies(): array
    {
        return $this->requireArray('dependencies');
    }
}
