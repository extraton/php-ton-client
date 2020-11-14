<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\FunctionHeaderParams;
use Extraton\TonClient\Entity\Abi\ResultOfAttachSignature;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeMessage;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfSign;
use Extraton\TonClient\Handler\Response;
use JsonException;

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
    /**
     * @covers ::decodeMessage
     */
    public function testDecodeMessageWithSuccessResult(): void
    {
        $abiJson = file_get_contents(__DIR__ . '/Data/Events.abi.json');
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
     * @covers ::decodeMessageBody
     */
    public function testDecodeMessageBodyWithSuccessResult(): void
    {
        $abiJson = file_get_contents(__DIR__ . '/Data/Events.abi.json');
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

    /**
     * @covers ::encodeMessage
     * @covers \Extraton\TonClient\Crypto::sign
     *
     * @throws JsonException
     */
    public function testEncodeMessage(): void
    {
        // Create unsigned deployment message
        $abi = AbiParams::fromJson($this->dataProvider->getEventsAbiJson());

        $deploySet = new DeploySetParams($this->dataProvider->getEventsTvc());

        $functionHeader = new FunctionHeaderParams(
            $this->dataProvider->getPublicKey(),
            $this->dataProvider->getEventsTime(),
            $this->dataProvider->getEventsExpire(),
        );

        $callSet = new CallSetParams('constructor', $functionHeader);

        $signer = SignerParams::fromExternal($this->dataProvider->getPublicKey());

        $expectedResult = [
            'message'      => 'te6ccgECFwEAA2gAAqeIAAt9aqvShfTon7Lei1PVOhUEkEEZQkhDKPgNyzeTL6YSEZTHxAj/Hd67jWQF7peccWoU/dbMCBJBB6YdPCVZcJlJkAAAF0ZyXLg19VzGRotV8/gGAQEBwAICA88gBQMBAd4EAAPQIABB2mPiBH+O713GsgL3S844tQp+62YECSCD0w6eEqy4TKTMAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=',
            'data_to_sign' => $dataToSign = 'KCGM36iTYuCYynk+Jnemis+mcwi3RFCke95i7l96s4Q=',
            'address'      => '0:05beb555e942fa744fd96f45a9ea9d0a8248208ca12421947c06e59bc997d309',
            'message_id'   => '448570e75b7d42d39c35fd47a42850ed560fe3a078d8708d231daaee2f56b777',
        ];

        $expected = new ResultOfEncodeMessage(new Response($expectedResult));

        self::assertEquals(
            $expected,
            $resultOfEncodeMessage = $this->abi->encodeMessage($abi, $signer, $deploySet, $callSet)
        );

        // Create detached signature
        $keyPair = new KeyPair(
            $this->dataProvider->getPublicKey(),
            $this->dataProvider->getPrivateKey()
        );

        $expectedResult = [
            'signed'    => 'YnI1e8y2Adsrghyw9fVkq1GSEtJCzzGWH+mjxQowsjYBJhgpa092k1XA6VZ80ls2bzwDdDXEmMguUwViKtvHDighjN+ok2LgmMp5PiZ3porPpnMIt0RQpHveYu5ferOE',
            'signature' => '6272357bccb601db2b821cb0f5f564ab519212d242cf31961fe9a3c50a30b236012618296b4f769355c0e9567cd25b366f3c037435c498c82e5305622adbc70e',
        ];

        $expected = new ResultOfSign(new Response($expectedResult));

        self::assertEquals(
            $expected,
            $resultOfSign = $this->crypto->sign($dataToSign, $keyPair)
        );

        $expectedResult = [
            'message'    => 'te6ccgECGAEAA6wAA0eIAAt9aqvShfTon7Lei1PVOhUEkEEZQkhDKPgNyzeTL6YSEbAHAgEA4bE5Gr3mWwDtlcEOWHr6slWoyQlpIWeYyw/00eKFGFkbAJMMFLWnu0mq4HSrPmktmzeeAboa4kxkFymCsRVt44dTHxAj/Hd67jWQF7peccWoU/dbMCBJBB6YdPCVZcJlJkAAAF0ZyXLg19VzGRotV8/gAQHAAwIDzyAGBAEB3gUAA9AgAEHaY+IEf47vXcayAvdLzji1Cn7rZgQJIIPTDp4SrLhMpMwCJv8A9KQgIsABkvSg4YrtU1gw9KEKCAEK9KQg9KEJAAACASANCwHI/38h7UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLTAAGOHYECANcYIPkBAdMAAZTT/wMBkwL4QuIg+GX5EPKoldMAAfJ64tM/AQwAao4e+EMhuSCfMCD4I4ED6KiCCBt3QKC53pL4Y+CANPI02NMfAfgjvPK50x8B8AH4R26S8jzeAgEgEw4CASAQDwC9uotV8/+EFujjXtRNAg10nCAY4Q0//TP9MA0X/4Yfhm+GP4Yo4Y9AVwAYBA9A7yvdcL//hicPhjcPhmf/hh4t74RvJzcfhm0fgA+ELIy//4Q88LP/hGzwsAye1Uf/hngCASASEQDluIAGtb8ILdHCfaiaGn/6Z/pgGi//DD8M3wx/DFvfSDK6mjofSBv6PwikDdJGDhvfCFdeXAyfABkZP2CEGRnwoRnRoIEB9AAAAAAAAAAAAAAAAAAIGeLZMCAQH2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8ADFuZPCot8ILdHCfaiaGn/6Z/pgGi//DD8M3wx/DFva4b/yupo6Gn/7+j8AGRF7gAAAAAAAAAAAAAAAAhni2fA58jjyxi9EOeF/+S4/YAYfCFkZf/8IeeFn/wjZ4WAZPaqP/wzwAgFIFxQBCbi3xYJQFQH8+EFujhPtRNDT/9M/0wDRf/hh+Gb4Y/hi3tcN/5XU0dDT/9/R+ADIi9wAAAAAAAAAAAAAAAAQzxbPgc+Rx5YxeiHPC//JcfsAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPklb4sEohzwv/yXH7ADD4QsjL//hDzws/+EbPCwDJ7VR/FgAE+GcActxwItDWAjHSADDcIccAkvI74CHXDR+S8jzhUxGS8jvhwQQighD////9vLGS8jzgAfAB+EdukvI83g==',
            'message_id' => 'd5b3375db0313656657ea7728e87c93fb07c4c246666fac22011fb1cb95a50c4',
        ];

        // Attach signature to unsigned message
        $expected = new ResultOfAttachSignature(new Response($expectedResult));

        self::assertEquals(
            $expected,
            $this->abi->attachSignature(
                $abi,
                $keyPair->getPublic(),
                $resultOfEncodeMessage->getMessage(),
                $resultOfSign->getSignature()
            )
        );

        // Create initially signed message
        $keyPair = new KeyPair($this->dataProvider->getPublicKey(), $this->dataProvider->getPrivateKey());
        $signer = SignerParams::fromKeys($keyPair);

        $expectedResult = [
            'message'      => 'te6ccgECGAEAA6wAA0eIAAt9aqvShfTon7Lei1PVOhUEkEEZQkhDKPgNyzeTL6YSEbAHAgEA4bE5Gr3mWwDtlcEOWHr6slWoyQlpIWeYyw/00eKFGFkbAJMMFLWnu0mq4HSrPmktmzeeAboa4kxkFymCsRVt44dTHxAj/Hd67jWQF7peccWoU/dbMCBJBB6YdPCVZcJlJkAAAF0ZyXLg19VzGRotV8/gAQHAAwIDzyAGBAEB3gUAA9AgAEHaY+IEf47vXcayAvdLzji1Cn7rZgQJIIPTDp4SrLhMpMwCJv8A9KQgIsABkvSg4YrtU1gw9KEKCAEK9KQg9KEJAAACASANCwHI/38h7UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLTAAGOHYECANcYIPkBAdMAAZTT/wMBkwL4QuIg+GX5EPKoldMAAfJ64tM/AQwAao4e+EMhuSCfMCD4I4ED6KiCCBt3QKC53pL4Y+CANPI02NMfAfgjvPK50x8B8AH4R26S8jzeAgEgEw4CASAQDwC9uotV8/+EFujjXtRNAg10nCAY4Q0//TP9MA0X/4Yfhm+GP4Yo4Y9AVwAYBA9A7yvdcL//hicPhjcPhmf/hh4t74RvJzcfhm0fgA+ELIy//4Q88LP/hGzwsAye1Uf/hngCASASEQDluIAGtb8ILdHCfaiaGn/6Z/pgGi//DD8M3wx/DFvfSDK6mjofSBv6PwikDdJGDhvfCFdeXAyfABkZP2CEGRnwoRnRoIEB9AAAAAAAAAAAAAAAAAAIGeLZMCAQH2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8ADFuZPCot8ILdHCfaiaGn/6Z/pgGi//DD8M3wx/DFva4b/yupo6Gn/7+j8AGRF7gAAAAAAAAAAAAAAAAhni2fA58jjyxi9EOeF/+S4/YAYfCFkZf/8IeeFn/wjZ4WAZPaqP/wzwAgFIFxQBCbi3xYJQFQH8+EFujhPtRNDT/9M/0wDRf/hh+Gb4Y/hi3tcN/5XU0dDT/9/R+ADIi9wAAAAAAAAAAAAAAAAQzxbPgc+Rx5YxeiHPC//JcfsAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPklb4sEohzwv/yXH7ADD4QsjL//hDzws/+EbPCwDJ7VR/FgAE+GcActxwItDWAjHSADDcIccAkvI74CHXDR+S8jzhUxGS8jvhwQQighD////9vLGS8jzgAfAB+EdukvI83g==',
            'data_to_sign' => null,
            'address'      => '0:05beb555e942fa744fd96f45a9ea9d0a8248208ca12421947c06e59bc997d309',
            'message_id'   => 'd5b3375db0313656657ea7728e87c93fb07c4c246666fac22011fb1cb95a50c4',
        ];

        $expected = new ResultOfEncodeMessage(new Response($expectedResult));

        self::assertEquals(
            $expected,
            $this->abi->encodeMessage(
                $abi,
                $signer,
                $deploySet,
                $callSet
            )
        );

        // Create run unsigned message
        $address = '0:05beb555e942fa744fd96f45a9ea9d0a8248208ca12421947c06e59bc997d309';

        $functionHeader = new FunctionHeaderParams(
            $this->dataProvider->getPublicKey(),
            $this->dataProvider->getEventsTime(),
            $this->dataProvider->getEventsExpire(),
        );

        $callSet = new CallSetParams(
            'returnValue',
            $functionHeader,
            ['id' => '0'],
        );

        $signer = SignerParams::fromExternal($this->dataProvider->getPublicKey());

        $expectedResult = [
            'message'      => 'te6ccgEBAgEAeAABpYgAC31qq9KF9Oifst6LU9U6FQSQQRlCSEMo+A3LN5MvphIFMfECP8d3ruNZAXul5xxahT91swIEkEHph08JVlwmUmQAAAXRnJcuDX1XMZBW+LBKAQBAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
            'data_to_sign' => 'i4Hs3PB12QA9UBFbOIpkG3JerHHqjm4LgvF4MA7TDsY=',
            'address'      => '0:05beb555e942fa744fd96f45a9ea9d0a8248208ca12421947c06e59bc997d309',
            'message_id'   => '0bf67f00d10145b2809663c599203fe2dc2cf75caf089afbaf33e51156230062',
        ];

        $expected = new ResultOfEncodeMessage(new Response($expectedResult));

        self::assertEquals(
            $expected,
            $this->abi->encodeMessage(
                $abi,
                $signer,
                null,
                $callSet,
                $address
            )
        );
    }
}
