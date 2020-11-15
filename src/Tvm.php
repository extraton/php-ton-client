<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Tvm\AccountForExecutor;
use Extraton\TonClient\Entity\Tvm\ExecutionOptions;
use Extraton\TonClient\Entity\Tvm\ResultOfRunExecutor;
use Extraton\TonClient\Entity\Tvm\ResultOfRunGet;
use Extraton\TonClient\Entity\Tvm\ResultOfRunTvm;
use Extraton\TonClient\Exception\TonException;

/**
 * Tvm module
 */
class Tvm extends AbstractModule
{
    /**
     * Run executor
     *
     * @param string $message
     * @param AccountForExecutor $accountForExecutor
     * @param ExecutionOptions|null $executionOptions
     * @param AbiParams|null $abi
     * @param bool|null $skipTransactionCheck
     * @return ResultOfRunExecutor
     * @throws TonException
     */
    public function runExecutor(
        string $message,
        AccountForExecutor $accountForExecutor,
        ?ExecutionOptions $executionOptions = null,
        ?AbiParams $abi = null,
        ?bool $skipTransactionCheck = null
    ): ResultOfRunExecutor {
        return new ResultOfRunExecutor(
            $this->tonClient->request(
                'tvm.run_executor',
                [
                    'message'                => $message,
                    'account'                => $accountForExecutor,
                    'execution_options'      => $executionOptions,
                    'abi'                    => $abi,
                    'skip_transaction_check' => $skipTransactionCheck,
                ]
            )->wait()
        );
    }

    /**
     * Run tvm
     *
     * @param string $message Input message BOC. Must be encoded as base64
     * @param string $account Account BOC. Must be encoded as base64
     * @param ExecutionOptions|null $executionOptions Execution options
     * @param AbiParams|null $abi Contract ABI for dedcoding output messages
     * @return ResultOfRunTvm
     * @throws TonException
     */
    public function runTvm(
        string $message,
        string $account,
        ?ExecutionOptions $executionOptions,
        ?AbiParams $abi
    ): ResultOfRunTvm {
        return new ResultOfRunTvm(
            $this->tonClient->request(
                'tvm.run_tvm',
                [
                    'message'           => $message,
                    'account'           => $account,
                    'execution_options' => $executionOptions,
                    'abi'               => $abi,
                ]
            )->wait()
        );
    }

    /**
     * Executes get method and returns data from TVM stack
     *
     * @param string $account Account BOC in base64
     * @param string $functionName Function name
     * @param ExecutionOptions|null $executionOptions Execution options
     * @param mixed $input Input parameters
     * @return ResultOfRunGet
     * @throws TonException
     */
    public function runGet(
        string $account,
        string $functionName,
        ?ExecutionOptions $executionOptions = null,
        $input = null
    ): ResultOfRunGet {
        return new ResultOfRunGet(
            $this->tonClient->request(
                'tvm.run_get',
                [
                    'account'           => $account,
                    'function_name'     => $functionName,
                    'execution_options' => $executionOptions,
                    'input'             => $input,
                ]
            )->wait()
        );
    }
}
