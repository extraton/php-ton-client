<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Boc\BuilderOp;
use Extraton\TonClient\Entity\Boc\CacheType;
use Extraton\TonClient\Entity\Boc\ResultOfBocCacheGet;
use Extraton\TonClient\Entity\Boc\ResultOfBocCacheSet;
use Extraton\TonClient\Entity\Boc\ResultOfEncodeBoc;
use Extraton\TonClient\Entity\Boc\ResultOfGetBlockchainConfig;
use Extraton\TonClient\Entity\Boc\ResultOfGetBocHash;
use Extraton\TonClient\Entity\Boc\ResultOfGetCodeFromTvc;
use Extraton\TonClient\Entity\Boc\ResultOfParse;
use Extraton\TonClient\Exception\TonException;

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
     * @throws TonException
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
     * @throws TonException
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
     * @throws TonException
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
     * @throws TonException
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
     * @throws TonException
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

    /**
     * Parses shardstate boc into a JSON
     * JSON structure is compatible with GraphQL API shardstate object
     *
     * @param string $boc BOC encoded as base64
     * @param string $id Shardstate identificator
     * @param int $workchainId Workchain shardstate belongs to
     * @return ResultOfParse
     * @throws TonException
     */
    public function parseShardstate(string $boc, string $id, int $workchainId): ResultOfParse
    {
        return new ResultOfParse(
            $this->tonClient->request(
                'boc.parse_shardstate',
                [
                    'boc'          => $boc,
                    'id'           => $id,
                    'workchain_id' => $workchainId,
                ]
            )->wait()
        );
    }

    /**
     * Calculates BOC root hash
     *
     * @param string $boc BOC encoded as base64
     * @return ResultOfGetBocHash
     * @throws TonException
     */
    public function getBocHash(string $boc): ResultOfGetBocHash
    {
        return new ResultOfGetBocHash(
            $this->tonClient->request(
                'boc.get_boc_hash',
                [
                    'boc' => $boc,
                ]
            )->wait()
        );
    }

    /**
     * Extracts code from TVC contract image
     *
     * @param string $tvc Contract TVC image encoded as base64
     * @return ResultOfGetCodeFromTvc
     * @throws TonException
     */
    public function getCodeFromTvc(string $tvc): ResultOfGetCodeFromTvc
    {
        return new ResultOfGetCodeFromTvc(
            $this->tonClient->request(
                'boc.get_code_from_tvc',
                [
                    'tvc' => $tvc,
                ]
            )->wait()
        );
    }

    /**
     * Get BOC from cache
     *
     * @param string $bocRef Reference to the cached BOC
     * @return ResultOfBocCacheGet
     * @throws TonException
     */
    public function cacheGet(string $bocRef): ResultOfBocCacheGet
    {
        return new ResultOfBocCacheGet(
            $this->tonClient->request(
                'boc.cache_get',
                [
                    'boc_ref' => $bocRef,
                ]
            )->wait()
        );
    }

    /**
     * Get BOC from cache
     *
     * @param string $boc BOC encoded as base64 or BOC reference
     * @param CacheType $cacheType Cache type
     * @return ResultOfBocCacheSet
     * @throws TonException
     */
    public function cacheSet(string $boc, CacheType $cacheType): ResultOfBocCacheSet
    {
        return new ResultOfBocCacheSet(
            $this->tonClient->request(
                'boc.cache_set',
                [
                    'boc'        => $boc,
                    'cache_type' => $cacheType,
                ]
            )->wait()
        );
    }

    /**
     * Unpin BOCs with specified pin
     *
     * @param string $pin Pinned name
     * @param string|null $bocRef Reference to the cached BOC (if it is provided then only referenced BOC is unpinned)
     * @return void
     * @throws TonException
     */
    public function cacheUnpin(string $pin, ?string $bocRef = null): void
    {
        $this->tonClient->request(
            'boc.cache_unpin',
            [
                'pin'     => $pin,
                'boc_ref' => $bocRef,
            ]
        )->wait();
    }

    /**
     * Encodes BOC from builder operations
     *
     * @param array<BuilderOp> $builderOps
     * @param CacheType|null $cacheType Cache type
     * @return ResultOfEncodeBoc
     * @throws TonException
     */
    public function encodeBoc(array $builderOps, ?CacheType $cacheType = null): ResultOfEncodeBoc
    {
        return new ResultOfEncodeBoc(
            $this->tonClient->request(
                'boc.encode_boc',
                [
                    'builder'    => $builderOps,
                    'cache_type' => $cacheType,
                ]
            )->wait()
        );
    }
}
