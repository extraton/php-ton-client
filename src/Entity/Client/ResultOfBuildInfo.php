<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Client;

use Extraton\TonClient\Entity\AbstractResult;

class ResultOfBuildInfo extends AbstractResult
{
    public function getBuildNumber(): int
    {
        return $this->requireInt('build_number');
    }

    public function getDependencies(): array
    {
        return $this->requireArray('dependencies');
    }
}
