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
                    'version' => '1.11.1'
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

        self::assertGreaterThanOrEqual(0, $resultOfBuildInfo->getBuildNumber());
        self::assertCount(0, $resultOfBuildInfo->getDependencies());
    }

    /**
     * @covers ::getApiReference
     */
    public function testGetApiReference(): void
    {
        $resultOfGetApiReference = $this->tonClient->getApiReference();

        self::assertEquals('1.11.1', $resultOfGetApiReference->getApi()['version']);
        self::assertCount(9, $resultOfGetApiReference->getApi()['modules']);
    }
}
