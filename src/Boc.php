<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Boc\ResultOfGetBlockchainConfig;
use Extraton\TonClient\Entity\Boc\ResultOfParse;

/**
 * Boc module
 */
class Boc extends AbstractModule
{
    /**
     * Parses message boc
     *
     * @param string $boc Message boc
     * @return ResultOfParse
     */
    public function parseMessage(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_message',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    /**
     * Parses transaction boc
     *
     * @param string $boc Transaction boc
     * @return ResultOfParse
     */
    public function parseTransaction(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_transaction',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    /**
     * Parses account boc
     *
     * @param string $boc Account boc
     * @return ResultOfParse
     */
    public function parseAccount(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_account',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    /**
     * Parses block boc
     *
     * @param string $boc Block boc
     * @return ResultOfParse
     */
    public function parseBlock(string $boc): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_block',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    /**
     * Get blockchain config
     *
     * @param string $blockBoc Block boc
     * @return ResultOfGetBlockchainConfig
     */
    public function getBlockchainConfig(string $blockBoc): ResultOfGetBlockchainConfig
    {
        return new ResultOfGetBlockchainConfig(
            $this->tonClient->request(
                'boc.get_blockchain_config',
                [
                    'block_boc' => $blockBoc,
                ]
            )->wait()
        );
    }
}
