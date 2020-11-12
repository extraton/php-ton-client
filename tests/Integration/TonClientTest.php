<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Entity\Client\ResultOfVersion;
use Extraton\TonClient\Handler\Response;

/**
 * Integration tests for Ton client
 *
 * @coversDefaultClass \Extraton\TonClient\TonClient
 */
class TonClientTest extends AbstractModuleTest
{
    /**
     * @covers ::version
     */
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

    /**
     * @covers ::buildInfo
     */
    public function testBuildInfoSuccessResult(): void
    {
        $resultOfBuildInfo = $this->tonClient->buildInfo();

        self::assertGreaterThanOrEqual(1017, $resultOfBuildInfo->getBuildNumber());
    }
}
