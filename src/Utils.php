<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;
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
}
