<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;

/**
 * Utils module
 */
class Utils
{
    private TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    /**
     * Convert Ton address
     *
     * @param string $address Ton address
     * @param AddressStringFormat $outputFormat Output format
     * @return ResultOfConvertAddress
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
     * Convert Ton address to account id
     *
     * @param string $address Ton address
     * @return ResultOfConvertAddress
     */
    public function convertAddressToAccountId(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::accountId()
        );
    }

    /**
     * Convert Ton address to hex
     *
     * @param string $address Ton address
     * @return ResultOfConvertAddress
     */
    public function convertAddressToHex(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::hex()
        );
    }

    /**
     * Convert Ton address to base64
     *
     * @param string $address Ton address
     * @param bool $url Is url
     * @param bool $test Is test
     * @param bool $bounce Is bounce
     * @return ResultOfConvertAddress
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
}
