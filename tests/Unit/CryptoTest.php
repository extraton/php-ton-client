<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfConvertPublicKeyToTonSafeFormat;
use Extraton\TonClient\Entity\Crypto\ResultOfFactorize;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateRandomBytes;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateSignKeys;
use Extraton\TonClient\Entity\Crypto\ResultOfHash;
use Extraton\TonClient\Entity\Crypto\ResultOfModularPower;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSign;
use Extraton\TonClient\Entity\Crypto\ResultOfScrypt;
use Extraton\TonClient\Entity\Crypto\ResultOfSign;
use Extraton\TonClient\Entity\Crypto\ResultOfTonCrc16;
use Extraton\TonClient\Entity\Crypto\ResultOfVerifySignature;
use Extraton\TonClient\Handler\Response;

use function microtime;
use function uniqid;
use function hexdec;

/**
 * Unit tests for Crypto module
 *
 * @coversDefaultClass \Extraton\TonClient\Crypto
 */
class CryptoTest extends AbstractModuleTest
{
    private Crypto $crypto;

    public function setUp(): void
    {
        parent::setUp();
        $this->crypto = new Crypto($this->mockTonClient);
    }

    /**
     * @covers ::factorize
     */
    public function testFactorizeWithSuccessResult(): void
    {
        $composite = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.factorize',
                [
                    'composite' => $composite,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfFactorize($result);

        self::assertEquals($expected, $this->crypto->factorize($composite));
    }

    /**
     * @covers ::modularPower
     */
    public function testModularPowerWithSuccessResult(): void
    {
        $base = uniqid(microtime(), true);
        $exponent = uniqid(microtime(), true);
        $modulus = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.modular_power',
                [
                    'base' => $base,
                    'exponent' => $exponent,
                    'modulus' => $modulus,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfModularPower($result);

        self::assertEquals($expected, $this->crypto->modularPower($base, $exponent, $modulus));
    }

    /**
     * @covers ::tonCrc16
     */
    public function testTonCrc16WithSuccessResult(): void
    {
        $data = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.ton_crc16',
                [
                    'data' => $data,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfTonCrc16($result);

        self::assertEquals($expected, $this->crypto->tonCrc16($data));
    }

    /**
     * @covers ::generateRandomBytes
     */
    public function testGenerateRandomBytesWithSuccessResult(): void
    {
        $length = hexdec(uniqid());
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.generate_random_bytes',
                [
                    'length' => $length,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateRandomBytes($result);

        self::assertEquals($expected, $this->crypto->generateRandomBytes($length));
    }

    /**
     * @covers ::convertPublicKeyToTonSafeFormat
     */
    public function testConvertPublicKeyToTonSafeFormatWithSuccessResult(): void
    {
        $publicKey = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.convert_public_key_to_ton_safe_format',
                [
                    'public_key' => $publicKey,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfConvertPublicKeyToTonSafeFormat($result);

        self::assertEquals($expected, $this->crypto->convertPublicKeyToTonSafeFormat($publicKey));
    }

    /**
     * @covers ::generateRandomSignKeys
     */
    public function testGenerateRandomSignKeysWithSuccessResult(): void
    {
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.generate_random_sign_keys',
                []
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateSignKeys($result);

        self::assertEquals($expected, $this->crypto->generateRandomSignKeys());
    }

    /**
     * @covers ::sign
     */
    public function testSignWithSuccessResult(): void
    {
        $unsigned = uniqid(microtime(), true);
        $keyPair = new KeyPair(uniqid(microtime(), true), uniqid(microtime(), true));
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.sign',
                [
                    'unsigned' => $unsigned,
                    'keys' => $keyPair,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSign($result);

        self::assertEquals($expected, $this->crypto->sign($unsigned, $keyPair));
    }

    /**
     * @covers ::verifySignature
     */
    public function testVerifySignatureWithSuccessResult(): void
    {
        $signed = uniqid(microtime(), true);
        $public = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.verify_signature',
                [
                    'signed' => $signed,
                    'public' => $public,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfVerifySignature($result);

        self::assertEquals($expected, $this->crypto->verifySignature($signed, $public));
    }

    /**
     * @covers ::sha256
     */
    public function testSha256WithSuccessResult(): void
    {
        $data = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.sha256',
                [
                    'data' => $data,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHash($result);

        self::assertEquals($expected, $this->crypto->sha256($data));
    }

    /**
     * @covers ::sha512
     */
    public function testSha512WithSuccessResult(): void
    {
        $data = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.sha512',
                [
                    'data' => $data,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHash($result);

        self::assertEquals($expected, $this->crypto->sha512($data));
    }

    /**
     * @covers ::scrypt
     */
    public function testScryptWithSuccessResult(): void
    {
        $password = uniqid(microtime(), true);
        $salt = uniqid(microtime(), true);
        $logN = hexdec(uniqid());
        $r = hexdec(uniqid());
        $p = hexdec(uniqid());
        $dkLen = hexdec(uniqid());
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.scrypt',
                [
                    'password' => $password,
                    'salt' => $salt,
                    'log_n' => $logN,
                    'r' => $r,
                    'p' => $p,
                    'dk_len' => $dkLen,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfScrypt($result);

        self::assertEquals($expected, $this->crypto->scrypt($password, $salt, $logN, $r, $p, $dkLen));
    }

    /**
     * @covers ::naclSignKeyPairFromSecretKey
     */
    public function testNaclSignKeyPairFromSecretKeyWithSuccessResult(): void
    {
        $secret = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.nacl_sign_keypair_from_secret_key',
                [
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateSignKeys($result);

        self::assertEquals($expected, $this->crypto->naclSignKeyPairFromSecretKey($secret));
    }

    /**
     * @covers ::naclSign
     */
    public function testNaclSignWithSuccessResult(): void
    {
        $unsigned = uniqid(microtime(), true);
        $secret = uniqid(microtime(), true);
        $result = new Response(
            [
                uniqid(microtime(), true)
            ]
        );

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with()
            ->willReturn($result);

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.nacl_sign',
                [
                    'unsigned' => $unsigned,
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSign($result);

        self::assertEquals($expected, $this->crypto->naclSign($unsigned, $secret));
    }
}
