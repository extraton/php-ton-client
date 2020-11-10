<?php

declare(strict_types=1);

namespace Tests\Integration\Extraton\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfConvertPublicKeyToTonSafeFormat;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateSignKeys;
use Extraton\TonClient\Entity\Crypto\ResultOfHash;
use Extraton\TonClient\Entity\Crypto\ResultOfModularPower;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSign;
use Extraton\TonClient\Entity\Crypto\ResultOfScrypt;
use Extraton\TonClient\Entity\Crypto\ResultOfSign;
use Extraton\TonClient\Entity\Crypto\ResultOfTonCrc16;
use Extraton\TonClient\Entity\Crypto\ResultOfVerifySignature;
use Extraton\TonClient\Handler\Response;

use function dechex;
use function array_map;
use function array_product;

/**
 * Integration tests for Utils module
 *
 * @coversDefaultClass \Extraton\TonClient\Crypto
 */
class CryptoTest extends AbstractModuleTest
{
    private Crypto $crypto;

    public function setUp(): void
    {
        parent::setUp();
        $this->crypto = $this->tonClient->getCrypto();
    }

    /**
     * @covers ::factorize
     */
    public function testFactorizeWithSuccessResult(): void
    {
        $number = 1724114033281923457;
        $numberHex = dechex($number);

        $result = $this->crypto->factorize($numberHex);

        $factors = $result->getFactors();
        $factorsDec = array_map('hexdec', $factors);
        $factorsDecProduct = array_product($factorsDec);
        self::assertEquals($number, $factorsDecProduct);
    }

    /**
     * @covers ::modularPower
     */
    public function testModularPowerWithSuccessResult(): void
    {
        $base = '0123456789ABCDEF';
        $exponent = '0123';
        $modulus = '01234567';

        $expected = new ResultOfModularPower(
            new Response(
                [
                    'modular_power' => '63bfdf',
                ]
            )
        );

        $result = $this->crypto->modularPower($base, $exponent, $modulus);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::tonCrc16
     */
    public function testTonCrc16WithSuccessResult(): void
    {
        $data = 'c2FtcGxlIGRhdGE=';

        $expected = new ResultOfTonCrc16(
            new Response(
                [
                    'crc' => '33694',
                ]
            )
        );

        $result = $this->crypto->tonCrc16($data);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::generateRandomBytes
     */
    public function testGenerateRandomBytesWithSuccessResult(): void
    {
        $length = 32;
        $expectedBytesHexLength = $length * 2;

        $result = $this->crypto->generateRandomBytes($length);
        $bytes = base64_decode($result->getBase64(), true);
        $bytesHex = bin2hex($bytes);
        $bytesHexLength = strlen($bytesHex);

        self::assertEquals($expectedBytesHexLength, $bytesHexLength);
    }

    /**
     * @covers ::convertPublicKeyToTonSafeFormat
     */
    public function testConvertPublicKeyToTonSafeFormatWithSuccessResult(): void
    {
        $publicKey = '25760703da66163fc4c189a95f807eb4363beb8d57cc95cea99fcd162b4ea536';

        $expected = new ResultOfConvertPublicKeyToTonSafeFormat(
            new Response(
                [
                    'ton_public_key' => 'PuYldgcD2mYWP8TBialfgH60NjvrjVfMlc6pn80WK06lNjzM',
                ]
            )
        );

        $result = $this->crypto->convertPublicKeyToTonSafeFormat($publicKey);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::generateRandomSignKeys
     */
    public function testGenerateRandomSignKeysWithSuccessResult(): void
    {
        $result = $this->crypto->generateRandomSignKeys();
        $keyPair = $result->getKeyPair();

        self::assertEquals(64, strlen($keyPair->getPublic()));
        self::assertEquals(64, strlen($keyPair->getSecret()));
    }

    /**
     * @covers ::sign
     */
    public function testSignWithSuccessResult(): void
    {
        $unsigned = 'dGVzdCBkYXRh';
        $keyPair = new KeyPair(
            '25760703da66163fc4c189a95f807eb4363beb8d57cc95cea99fcd162b4ea536',
            '48eeb4c7e4e2358b1e32db9920c56fe91a70cbf34c1dc607d73e0e3e9c4269d6'
        );

        $expected = new ResultOfSign(
            new Response(
                [
                    'signed' => 'spsrMiTVkuTZA1SynA43GkLyR4bKa/BpfPA+S8tiJP924+C53WPi6gES8Wek7YLDCP9cxqO1NmHTI3+7EnToDXRlc3QgZGF0YQ==',
                    'signature' => 'b29b2b3224d592e4d90354b29c0e371a42f24786ca6bf0697cf03e4bcb6224ff76e3e0b9dd63e2ea0112f167a4ed82c308ff5cc6a3b53661d3237fbb1274e80d',
                ]
            )
        );

        $result = $this->crypto->sign($unsigned, $keyPair);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::verifySignature
     */
    public function testVerifySignatureWithSuccessResult(): void
    {
        $signed = 'spsrMiTVkuTZA1SynA43GkLyR4bKa/BpfPA+S8tiJP924+C53WPi6gES8Wek7YLDCP9cxqO1NmHTI3+7EnToDXRlc3QgZGF0YQ==';
        $public = '25760703da66163fc4c189a95f807eb4363beb8d57cc95cea99fcd162b4ea536';

        $expected = new ResultOfVerifySignature(
            new Response(
                [
                    'unsigned' => 'dGVzdCBkYXRh',
                ]
            )
        );

        $result = $this->crypto->verifySignature($signed, $public);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::sha256
     */
    public function testSha256WithSuccessResult(): void
    {
        $data = 'TWVzc2FnZSB0byBoYXNo';

        $expected = new ResultOfHash(
            new Response(
                [
                    'hash' => 'f1aa45b0f5f6703468f9b9bc2b9874d4fa6b001a170d0f132aa5a26d00d0c7e5',
                ]
            )
        );

        $result = $this->crypto->sha256($data);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::sha512
     */
    public function testSha512WithSuccessResult(): void
    {
        $data = 'TWVzc2FnZSB0byBoYXNo';

        $expected = new ResultOfHash(
            new Response(
                [
                    'hash' => '36c7e42ae9f0e4c3c91753192ea1fbe80410dd809a3f235ccf954c6c95bbc6f0752fbc77d43f13036edc7f486e866b84cff16f9b62b0deb0bf61c373f048e45e',
                ]
            )
        );

        $result = $this->crypto->sha512($data);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::scrypt
     */
    public function testScryptWithSuccessResult(): void
    {
        $password = base64_encode('Test Password');
        $salt = base64_encode('Test Salt');
        $logN = 10;
        $r = 8;
        $p = 16;
        $dkLen = 64;

        $expected = new ResultOfScrypt(
            new Response(
                [
                    'key' => '52e7fcf91356eca55fc5d52f16f5d777e3521f54e3c570c9bbb7df58fc15add73994e5db42be368de7ebed93c9d4f21f9be7cc453358d734b04a057d0ed3626d',
                ]
            )
        );

        $result = $this->crypto->scrypt($password, $salt, $logN, $r, $p, $dkLen);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclSignKeyPairFromSecretKey
     */
    public function testNaclSignKeyPairFromSecretKeyWithSuccessResult(): void
    {
        $secret = 'e207b5966fb2c5be1b71ed94ea813202706ab84253bdf4dc55232f82a1caf0d4';

        $expected = new ResultOfGenerateSignKeys(
            new Response(
                [
                    'public' => '98550c56f41ecce3ab5715c1ad23d5dfb33dcc59e115cea9eef465c44e76fb89',
                    'secret' => 'e207b5966fb2c5be1b71ed94ea813202706ab84253bdf4dc55232f82a1caf0d498550c56f41ecce3ab5715c1ad23d5dfb33dcc59e115cea9eef465c44e76fb89',
                ]
            )
        );

        $result = $this->crypto->naclSignKeyPairFromSecretKey($secret);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclSign
     */
    public function testNaclSignWithSuccessResult(): void
    {
        $unsigned = base64_encode('Test Message');
        $secret = '56b6a77093d6fdf14e593f36275d872d75de5b341942376b2a08759f3cbae78f1869b7ef29d58026217e9cf163cbfbd0de889bdf1bf4daebf5433a312f5b8d6e';

        $expected = new ResultOfNaclSign(
            new Response(
                [
                    'signed' => '+wz+QO6l1slgZS5s65BNqKcu4vz24FCJz4NSAxef9lu0jFfs8x3PzSZRC+pn5k8+aJi3xYMA3BQzglQmjK3hA1Rlc3QgTWVzc2FnZQ==',
                ]
            )
        );

        $result = $this->crypto->naclSign($unsigned, $secret);

        self::assertEquals($expected, $result);
    }
}
