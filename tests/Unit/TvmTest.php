<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Tvm\AccountForExecutor;
use Extraton\TonClient\Entity\Tvm\ExecutionOptions;
use Extraton\TonClient\Entity\Tvm\ResultOfRunExecutor;
use Extraton\TonClient\Entity\Tvm\ResultOfRunGet;
use Extraton\TonClient\Entity\Tvm\ResultOfRunTvm;
use Extraton\TonClient\Handler\Response;
use Extraton\TonClient\Tvm;
use PHPUnit\Framework\MockObject\MockObject;

use function microtime;
use function random_int;
use function uniqid;

/**
 * Unit tests for Tvm module
 *
 * @coversDefaultClass \Extraton\TonClient\Tvm
 */
class TvmTest extends AbstractModuleTest
{
    private Tvm $tvm;

    public function setUp(): void
    {
        parent::setUp();
        $this->tvm = new Tvm($this->mockTonClient);
    }

    /**
     * @covers ::runExecutor
     */
    public function testRunExecutorWithSuccessResult(): void
    {
        $message = uniqid(microtime(), true);
        $accountForExecutor = AccountForExecutor::fromNone();

        /** @var MockObject|ExecutionOptions $executionOptions */
        $executionOptions = $this->getMockBuilder(ExecutionOptions::class)
            ->disableOriginalConstructor()
            ->getMock();

        $abi = AbiParams::fromArray([]);
        $skipTransactionCheck = (bool)random_int(0, 1);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'tvm.run_executor',
                [
                    'message'                => $message,
                    'account'                => $accountForExecutor,
                    'execution_options'      => $executionOptions,
                    'abi'                    => $abi,
                    'skip_transaction_check' => $skipTransactionCheck,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfRunExecutor($response);

        self::assertEquals(
            $expected,
            $this->tvm->runExecutor(
                $message,
                $accountForExecutor,
                $executionOptions,
                $abi,
                $skipTransactionCheck
            )
        );
    }

    /**
     * @covers ::runTvm
     */
    public function testRunTvmWithSuccessResult(): void
    {
        $message = uniqid(microtime(), true);
        $account = uniqid(microtime(), true);

        /** @var MockObject|ExecutionOptions $executionOptions */
        $executionOptions = $this->getMockBuilder(ExecutionOptions::class)
            ->disableOriginalConstructor()
            ->getMock();

        $abi = AbiParams::fromArray([]);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'tvm.run_tvm',
                [
                    'message'           => $message,
                    'account'           => $account,
                    'execution_options' => $executionOptions,
                    'abi'               => $abi,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfRunTvm($response);

        self::assertEquals(
            $expected,
            $this->tvm->runTvm(
                $message,
                $account,
                $executionOptions,
                $abi
            )
        );
    }

    /**
     * @covers ::runGet
     */
    public function testRunGetWithSuccessResult(): void
    {
        $account = uniqid(microtime(), true);
        $functionName = uniqid(microtime(), true);

        /** @var MockObject|ExecutionOptions $executionOptions */
        $executionOptions = $this->getMockBuilder(ExecutionOptions::class)
            ->disableOriginalConstructor()
            ->getMock();

        $input = uniqid(microtime(), true);

        $response = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($response);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'tvm.run_get',
                [
                    'account'           => $account,
                    'function_name'     => $functionName,
                    'execution_options' => $executionOptions,
                    'input'             => $input,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfRunGet($response);

        self::assertEquals(
            $expected,
            $this->tvm->runGet(
                $account,
                $functionName,
                $executionOptions,
                $input
            )
        );
    }
}
