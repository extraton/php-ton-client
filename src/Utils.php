<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Entity\Utils\AddressStringFormat;
use Extraton\TonClient\Entity\Utils\ResultOfConvertAddress;

class Utils
{
    private TonClient $tonClient;

    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

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

    public function convertAddressToAccountId(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::accountId()
        );
    }

    public function convertAddressToHex(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            $address,
            AddressStringFormat::hex()
        );
    }

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
