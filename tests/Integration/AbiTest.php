<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Exception;
use Extraton\TonClient\Abi;
use Extraton\TonClient\Boc;
use Extraton\TonClient\Entity\Abi\AbiParams;
use Extraton\TonClient\Entity\Abi\CallSetParams;
use Extraton\TonClient\Entity\Abi\DecodedMessageBody;
use Extraton\TonClient\Entity\Abi\DeploySetParams;
use Extraton\TonClient\Entity\Abi\FunctionHeaderParams;
use Extraton\TonClient\Entity\Abi\MessageSource;
use Extraton\TonClient\Entity\Abi\ResultOfEncodeAccount;
use Extraton\TonClient\Entity\Abi\SignerParams;
use Extraton\TonClient\Entity\Abi\StateInitSource;
use Extraton\TonClient\Entity\Crypto\KeyPairParams;
use Extraton\TonClient\Exception\RequestException;
use Extraton\TonClient\Handler\Response;
use Generator;
use JsonException;
use Tests\Integration\Extraton\TonClient\Data\DataProvider;

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
     * @dataProvider encodeAccountDataProvider
     *
     * @covers ::encodeAccount
     *
     * @param array $expectedResult
     * @param StateInitSource $stateInitSource
     * @param Exception|null $exception
     */
    public function testEncodeAccountWithSuccessResult(
        array $expectedResult,
        StateInitSource $stateInitSource,
        ?Exception $exception = null
    ): void {
        $expected = new ResultOfEncodeAccount(new Response($expectedResult));

        if ($exception !== null) {
            $this->expectExceptionObject($exception);
        }

        self::assertEquals($expected, $this->abi->encodeAccount($stateInitSource));
    }

    /**
     * @return Generator
     * @throws JsonException
     */
    public function encodeAccountDataProvider(): Generator
    {
        $dataProvider = new DataProvider();

        // Case 1
        $abi = AbiParams::fromJson($dataProvider->getEventsAbiJson());

        $encodedDeployMessage = 'te6ccgECFwEAA2gAAqeIAAt9aqvShfTon7Lei1PVOhUEkEEZQkhDKPgNyzeTL6YSEZTHxAj/Hd67jWQF7peccWoU/dbMCBJBB6YdPCVZcJlJkAAAF0ZyXLg19VzGRotV8/gGAQEBwAICA88gBQMBAd4EAAPQIABB2mPiBH+O713GsgL3S844tQp+62YECSCD0w6eEqy4TKTMAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=';
        $messageSource = MessageSource::fromEncoded($encodedDeployMessage, $abi);
        $stateInitSource = StateInitSource::fromMessage($messageSource);

        $expectedResult = [
            'account' => 'te6ccgECFwEAA00AAnHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACLoWrAAAAAAAAAAAAAAAAAUXSHboAE0AGAQEBwAICA88gBQMBAd4EAAPQIABB2mPiBH+O713GsgL3S844tQp+62YECSCD0w6eEqy4TKTMAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=',
            'id'      => '05beb555e942fa744fd96f45a9ea9d0a8248208ca12421947c06e59bc997d309'
        ];

        yield [
            $expectedResult,
            $stateInitSource,
        ];

        // Case 2
        $deploySet = new DeploySetParams($dataProvider->getEventsTvc());
        $functionHeader = new FunctionHeaderParams(
            $dataProvider->getEventsExpire(),
            $dataProvider->getEventsTime(),
            $dataProvider->getPublicKey(),
        );
        $callSet = new CallSetParams(
            'constructor',
            $functionHeader
        );
        $keyPair = new KeyPairParams(
            $dataProvider->getPublicKey(),
            $dataProvider->getPrivateKey(),
        );
        $signer = SignerParams::fromKeys($keyPair);

        $abi = AbiParams::fromJson($dataProvider->getEventsAbiJson());
        $messageSource = MessageSource::fromEncodingParams($abi, $signer, $deploySet, $callSet);
        $stateInitSource = StateInitSource::fromMessage($messageSource);

        $expectedResult = [
            'account' => 'te6ccgECFwEAA00AAnHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACLoWrAAAAAAAAAAAAAAAAAUXSHboAE0AGAQEBwAICA88gBQMBAd4EAAPQIABB3oqFDmdVlZoN9kc118zuAo7QJh5PPinpjaykeQU2fMwsAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=',
            'id'      => 'e7ab0608f3f3458f191b23fa1255234ce1f4ce31430e121a93c19740454b7452',
        ];

        yield [
            $expectedResult,
            $stateInitSource,
        ];

        // Case 3: External signer
        $signer = SignerParams::fromExternal($dataProvider->getPublicKey());
        $abi = AbiParams::fromJson($dataProvider->getEventsAbiJson());
        $messageSource = MessageSource::fromEncodingParams($abi, $signer, $deploySet, $callSet);
        $stateInitSource = StateInitSource::fromMessage($messageSource);

        yield [
            [],
            $stateInitSource,
            new RequestException(
                'Function `process_message` must not be used with external message signing.',
                513
            )
        ];

        // Case 4
        $stateInitSource = StateInitSource::fromTvc($dataProvider->getEventsTvc());

        $expectedResult = [
            'account' => 'te6ccgECFwEAA00AAnHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACLoWrAAAAAAAAAAAAAAAAAUXSHboAE0AGAQEBwAICA88gBQMBAd4EAAPQIABB2AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=',
            'id'      => '84a9510b0278047154b1b84b6dd445c1349d8d42d75a2eece07b72ad6e4ea136',
        ];

        yield [
            $expectedResult,
            $stateInitSource
        ];

        // Case 5
        $stateInitSource = StateInitSource::fromTvc($dataProvider->getEventsTvc(), $dataProvider->getPublicKey());

        $expectedResult = [
            'account' => 'te6ccgECFwEAA00AAnHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACLoWrAAAAAAAAAAAAAAAAAUXSHboAE0AGAQEBwAICA88gBQMBAd4EAAPQIABB3oqFDmdVlZoN9kc118zuAo7QJh5PPinpjaykeQU2fMwsAib/APSkICLAAZL0oOGK7VNYMPShCQcBCvSkIPShCAAAAgEgDAoByP9/Ie1E0CDXScIBjhDT/9M/0wDRf/hh+Gb4Y/hijhj0BXABgED0DvK91wv/+GJw+GNw+GZ/+GHi0wABjh2BAgDXGCD5AQHTAAGU0/8DAZMC+ELiIPhl+RDyqJXTAAHyeuLTPwELAGqOHvhDIbkgnzAg+COBA+iogggbd0Cgud6S+GPggDTyNNjTHwH4I7zyudMfAfAB+EdukvI83gIBIBINAgEgDw4AvbqLVfP/hBbo417UTQINdJwgGOENP/0z/TANF/+GH4Zvhj+GKOGPQFcAGAQPQO8r3XC//4YnD4Y3D4Zn/4YeLe+Ebyc3H4ZtH4APhCyMv/+EPPCz/4Rs8LAMntVH/4Z4AgEgERAA5biABrW/CC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb30gyupo6H0gb+j8IpA3SRg4b3whXXlwMnwAZGT9ghBkZ8KEZ0aCBAfQAAAAAAAAAAAAAAAAACBni2TAgEB9gBh8IWRl//wh54Wf/CNnhYBk9qo//DPAAxbmTwqLfCC3Rwn2omhp/+mf6YBov/ww/DN8Mfwxb2uG/8rqaOhp/+/o/ABkRe4AAAAAAAAAAAAAAAAIZ4tnwOfI48sYvRDnhf/kuP2AGHwhZGX//CHnhZ/8I2eFgGT2qj/8M8AIBSBYTAQm4t8WCUBQB/PhBbo4T7UTQ0//TP9MA0X/4Yfhm+GP4Yt7XDf+V1NHQ0//f0fgAyIvcAAAAAAAAAAAAAAAAEM8Wz4HPkceWMXohzwv/yXH7AMiL3AAAAAAAAAAAAAAAABDPFs+Bz5JW+LBKIc8L/8lx+wAw+ELIy//4Q88LP/hGzwsAye1UfxUABPhnAHLccCLQ1gIx0gAw3CHHAJLyO+Ah1w0fkvI84VMRkvI74cEEIoIQ/////byxkvI84AHwAfhHbpLyPN4=',
            'id'      => 'e7ab0608f3f3458f191b23fa1255234ce1f4ce31430e121a93c19740454b7452',
        ];

        yield [
            $expectedResult,
            $stateInitSource
        ];
    }
}
