<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\TonClient;
use GuzzleHttp\Promise\Promise;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractModuleTest extends TestCase
{
    /** @var MockObject|TonClient */
    protected MockObject $mockTonClient;

    /** @var MockObject|Promise */
    protected MockObject $mockPromise;

    public function setUp(): void
    {
        $this->mockTonClient = $this->getMockBuilder(TonClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'request'
                ]
            )
            ->getMock();

        $this->mockPromise = $this->getMockBuilder(Promise::class)
            ->disableOriginalConstructor()
            ->onlyMethods(
                [
                    'wait'
                ]
            )
            ->getMock();
    }
}
