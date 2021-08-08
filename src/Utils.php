<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\AbstractResult;
use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfCalcStorageFee;
use Extraton\TonClient\Entity\Utils\ResultOfCompressZstd;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
use Extraton\TonClient\Entity\Utils\ResultOfDecompressZstd;
use Extraton\TonClient\Entity\Utils\ResultOfGetAddressType;
use Extraton\TonClient\Exception\TonException;

/**
 * Utils module
 */
class Utils extends AbstractModule
{
    /**
     * Converts address from any TON format to any TON format
     *
     * @param string $address Account address in any TON format
     * @param AddressStringFormat $outputFormat Specify the format to convert to
     * @return ResultOfConvertAddress
     * @throws TonException
     */
    public function convertAddress(string $address, AddressStringFormat $outputFormat): ResultOfConvertAddress
    {
        return new ResultOfConvertAddress(
            $this->tonClient->request(
                'utils.convert_address',
                [
                    'address'       => $address,
                    'output_format' => $outputFormat,
                ]
            )->wait()
        );
    }

    /**
     * Converts address from any TON format to AccountID
     *
     * @param string $address Account address in any TON format
     * @return ResultOfConvertAddress
     * @throws TonException
     */
    public function convertAddressToAccountId(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::accountId()
        );
    }

    /**
     * Converts address from any TON format to HEX
     *
     * @param string $address Account address in any TON format
     * @return ResultOfConvertAddress
     * @throws TonException
     */
    public function convertAddressToHex(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::hex()
        );
    }

    /**
     * Converts address from any TON format to Base64
     *
     * @param string $address Account address in any TON format
     * @param bool $url Is url
     * @param bool $test Is test
     * @param bool $bounce Is bounce
     * @return ResultOfConvertAddress
     * @throws TonException
     */
    public function convertAddressToBase64(
        string $address,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ): ResultOfConvertAddress {
        return $this->convertAddress(
            $address,
            AddressStringFormat::base64($url, $test, $bounce)
        );
    }

    /**
     * Calculates storage fee for an account over a specified time period
     *
     * @param string $account Account
     * @param int $period Period
     * @return ResultOfCalcStorageFee
     * @throws TonException
     */
    public function calcStorageFee(string $account, int $period): ResultOfCalcStorageFee
    {
        return new ResultOfCalcStorageFee(
            $this->tonClient->request(
                'utils.calc_storage_fee',
                [
                    'account' => $account,
                    'period'  => $period,
                ]
            )->wait()
        );
    }

    /**
     * Compresses data using Zstandard algorithm
     * Where: 1 - lowest compression level (fastest compression); 21 - highest compression level (slowest compression).
     * If level is omitted, the default compression level is used (currently 3).
     *
     * @param string $uncompressed Uncompressed data. Must be encoded as base64.
     * @param int|null $level Compression level, from 1 to 21.
     * @return ResultOfCompressZstd
     * @throws TonException
     */
    public function compressZstd(string $uncompressed, ?int $level = null): ResultOfCompressZstd
    {
        return new ResultOfCompressZstd(
            $this->tonClient->request(
                'utils.compress_zstd',
                [
                    'uncompressed' => $uncompressed,
                    'level'        => $level,
                ]
            )->wait()
        );
    }

    /**
     * Decompresses data using Zstandard algorithm
     *
     * @param string $compressed Compressed data. Must be encoded as base64.
     * @return ResultOfDecompressZstd
     * @throws TonException
     * @throws TonException
     */
    public function decompressZstd(string $compressed): ResultOfDecompressZstd
    {
        return new ResultOfDecompressZstd(
            $this->tonClient->request(
                'utils.decompress_zstd',
                [
                    'compressed' => $compressed,
                ]
            )->wait()
        );
    }

    /**
     * Validates and returns the type of any TON address.
     *
     * @param string $address Account address in any TON format.
     * @return ResultOfGetAddressType
     * @throws TonException
     */
    public function getAddressType(string $address): ResultOfGetAddressType
    {
        return new ResultOfGetAddressType(
            $this->tonClient->request(
                'utils.get_address_type',
                [
                    'address' => $address,
                ]
            )->wait()
        );
    }
}
