<?php

declare(strict_types=1);

namespace Extraton\TonClient;

use Extraton\TonClient\Params\Utils\ParamsOfConvertAddress;
use Extraton\TonClient\Result\Utils\ResultOfConvertAddress;

class Utils
{
    private TonClient $tonClient;

    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    public function convertAddress(ParamsOfConvertAddress $params): ResultOfConvertAddress
    {
        return new ResultOfConvertAddress(
            $this->tonClient->request(
                'utils.convert_address',
                $params->jsonSerialize()
            )->wait()
        );
    }

    public function convertAddressToAccountId(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            new ParamsOfConvertAddress(
                $address,
                ParamsOfConvertAddress::TYPE_ACCOUNT_ID
            )
        );
    }

    public function convertAddressToHex(string $address): ResultOfConvertAddress
    {
        return $this->convertAddress(
            new ParamsOfConvertAddress(
                $address,
                ParamsOfConvertAddress::TYPE_HEX
            )
        );
    }

    public function convertAddressToBase64(
        string $address,
        bool $url = false,
        bool $test = false,
        bool $bounce = false
    ): ResultOfConvertAddress {
        return $this->convertAddress(
            new ParamsOfConvertAddress(
                $address,
                ParamsOfConvertAddress::TYPE_BASE64,
                $url,
                $test,
                $bounce
            )
        );
    }
}
