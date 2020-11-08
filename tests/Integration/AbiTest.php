<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Abi;
use Extraton\TonClient\Boc;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Handler\Response;

use function file_get_contents;
use function json_decode;

use const JSON_THROW_ON_ERROR;

/**
 * Integration tests for Abi module
 *
 * @coversDefaultClass \Extraton\TonClient\Abi
 */
class AbiTest extends AbstractModuleTest
{
    private Abi $abi;

    private Boc $boc;

    public function setUp(): void
    {
        parent::setUp();
        $this->abi = $this->tonClient->getAbi();
        $this->boc = $this->tonClient->getBoc();
    }

    /**
     * @covers ::decodeMessage
     * @covers ::decodeMessageFromJson
     * @covers ::decodeMessageFromArray
     */
    public function testDecodeMessageWithSuccessResult(): void
    {
        $abiJson = file_get_contents(__DIR__ . '/data/Events.abi.json');
        $abiArray = json_decode($abiJson, true, 32, JSON_THROW_ON_ERROR);

        $abiFromJson = AbiParams::fromJson($abiJson);
        $abiFromArray = AbiParams::fromArray($abiArray);

        $message = 'te6ccgEBAwEAvAABRYgAC31qq9KF9Oifst6LU9U6FQSQQRlCSEMo+A3LN5MvphIMAQHhrd/b+MJ5Za+AygBc5qS/dVIPnqxCsM9PvqfVxutK+lnQEKzQoRTLYO6+jfM8TF4841bdNjLQwIDWL4UVFdxIhdMfECP8d3ruNZAXul5xxahT91swIEkEHph08JVlwmUmQAAAXRnJcuDX1XMZBW+LBKACAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==';

        $expected = new DecodedMessageBody(
            new Response(
                [
                    'body_type' => 'Input',
                    'name'      => 'returnValue',
                    'value'     =>
                        [
                            'id' => '0x0',
                        ],
                    'header'    =>
                        [
                            'expire' => 1599458404,
                            'time'   => 1599458364291,
                            'pubkey' => '4c7c408ff1ddebb8d6405ee979c716a14fdd6cc08124107a61d3c25597099499',
                        ],
                ]
            )
        );

        self::assertEquals($expected, $this->abi->decodeMessage($abiFromJson, $message));
        self::assertEquals($expected, $this->abi->decodeMessage($abiFromArray, $message));
    }

    /**
     * @covers ::
     */
    public function testDecodeMessageBodyWithSuccessResult(): void
    {
        $abiJson = file_get_contents(__DIR__ . '/data/Events.abi.json');
        $abiArray = json_decode($abiJson, true, 32, JSON_THROW_ON_ERROR);

        $abiFromJson = AbiParams::fromJson($abiJson);
        $abiFromArray = AbiParams::fromArray($abiArray);

        $message = 'te6ccgEBAwEAvAABRYgAC31qq9KF9Oifst6LU9U6FQSQQRlCSEMo+A3LN5MvphIMAQHhrd/b+MJ5Za+AygBc5qS/dVIPnqxCsM9PvqfVxutK+lnQEKzQoRTLYO6+jfM8TF4841bdNjLQwIDWL4UVFdxIhdMfECP8d3ruNZAXul5xxahT91swIEkEHph08JVlwmUmQAAAXRnJcuDX1XMZBW+LBKACAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==';
        $parsed = $this->boc->parseMessage($message)->getParsed();
        $body = $parsed['body'] ?? '';

        $expected = new DecodedMessageBody(
            new Response(
                [
                    'body_type' => 'Input',
                    'name'      => 'returnValue',
                    'value'     =>
                        [
                            'id' => '0x0',
                        ],
                    'header'    =>
                        [
                            'expire' => 1599458404,
                            'time'   => 1599458364291,
                            'pubkey' => '4c7c408ff1ddebb8d6405ee979c716a14fdd6cc08124107a61d3c25597099499',
                        ],
                ]
            )
        );

        self::assertEquals($expected, $this->abi->decodeMessageBody($abiFromJson, $body));
        self::assertEquals($expected, $this->abi->decodeMessageBody($abiFromArray, $body));
    }
}
