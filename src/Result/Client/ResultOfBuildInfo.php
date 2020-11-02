<?php

declare(strict_types=1);

namespace Extraton\TonClient\Result\Client;

use Extraton\TonClient\Result\AbstractResult;

class ResultOfBuildInfo extends AbstractResult
{
    public function getBuildInfo(): array
    {
        return $this->getResult();
    }

    public function getBuildNumber(): int
    {
        return $this->requireInt('build_info', 'buildNumber');
    }

    public function getTonLabsTypesGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-types', 'git-commit');
    }

    public function getTonLabsBlockGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-block', 'git-commit');
    }

    public function getTonLabsBlockJsonGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-block-json', 'git-commit');
    }

    public function getTonLabsVmGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-vm', 'git-commit');
    }

    public function getTonLabsAbiGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-abi', 'git-commit');
    }

    public function getTonLabsExecutorGitCommit(): string
    {
        return $this->requireString('build_info', 'ton-labs-executor', 'git-commit');
    }
}
