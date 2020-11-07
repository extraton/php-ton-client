<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Entity\Client\ResultOfBuildInfo;
use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Handler\Response;

class TonClientModuleTest extends AbstractModuleTest
{
    public function testVersionWithSuccessResult(): void
    {
        $expected = new ResultOfVersion(
            new Response(
                [
                    'version' => '1.0.0'
                ]
            )
        );

        self::assertEquals($expected, $this->tonClient->version());
    }

    public function testBuildInfoSuccessResult(): void
    {
        $expected = new ResultOfBuildInfo(
            new Response(
                [
                    'build_info' =>
                        [
                            'buildNumber'         => 839,
                            'ton-labs-types'      =>
                                [
                                    'git-commit' => '51966d1a684edaa6895a8cf54f1672dcf961cc54',
                                ],
                            'ton-labs-block'      =>
                                [
                                    'git-commit' => '9b01940e207d9ba214bb69c8e9eb45e64f5dce2b',
                                ],
                            'ton-labs-block-json' =>
                                [
                                    'git-commit' => 'f5cd84fc93afd44ad3e57bdc11392031967d08f3',
                                ],
                            'ton-labs-vm'         =>
                                [
                                    'git-commit' => '87a1d1ce336cb5d310fd631d99e293c6599021f1',
                                ],
                            'ton-labs-abi'        =>
                                [
                                    'git-commit' => '04f832045593b43a46f71f6a2dd2c8fad8295a23',
                                ],
                            'ton-labs-executor'   =>
                                [
                                    'git-commit' => 'dd6db9023e69cf77fe500fa9cdd10eabdcaed23b',
                                ],
                        ],
                ]
            )
        );

        self::assertEquals($expected, $this->tonClient->buildInfo());
    }
}
