<?php

declare(strict_types=1);

namespace Extraton\Tests\Integration\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfConvertPublicKeyToTonSafeFormat;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateMnemonic;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateSignKeys;
use Extraton\TonClient\Entity\Crypto\ResultOfHash;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeyPublicFromXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeySecretFromXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeyXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfMnemonicVerify;
use Extraton\TonClient\Entity\Crypto\ResultOfModularPower;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclBox;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclBoxOpen;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSign;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSignDetached;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSignOpen;
use Extraton\TonClient\Entity\Crypto\ResultOfScrypt;
use Extraton\TonClient\Entity\Crypto\ResultOfSign;
use Extraton\TonClient\Entity\Crypto\ResultOfTonCrc16;
use Extraton\TonClient\Entity\Crypto\ResultOfVerifySignature;
use Extraton\TonClient\Handler\Response;

use function array_map;
use function array_product;
use function count;
use function dechex;
use function explode;

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

    /**
     * @covers ::naclSignOpen
     */
    public function testNaclSignOpenWithSuccessResult(): void
    {
        $signed = '+wz+QO6l1slgZS5s65BNqKcu4vz24FCJz4NSAxef9lu0jFfs8x3PzSZRC+pn5k8+aJi3xYMA3BQzglQmjK3hA1Rlc3QgTWVzc2FnZQ==';
        $public = '1869b7ef29d58026217e9cf163cbfbd0de889bdf1bf4daebf5433a312f5b8d6e';

        $expected = new ResultOfNaclSignOpen(
            new Response(
                [
                    'unsigned' => base64_encode('Test Message'),
                ]
            )
        );

        $result = $this->crypto->naclSignOpen($signed, $public);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclSignDetached
     */
    public function testNaclSignDetachedWithSuccessResult(): void
    {
        $unsigned = base64_encode('Test Message');
        $secret = '56b6a77093d6fdf14e593f36275d872d75de5b341942376b2a08759f3cbae78f1869b7ef29d58026217e9cf163cbfbd0de889bdf1bf4daebf5433a312f5b8d6e';

        $expected = new ResultOfNaclSignDetached(
            new Response(
                [
                    'signature' => 'fb0cfe40eea5d6c960652e6ceb904da8a72ee2fcf6e05089cf835203179ff65bb48c57ecf31dcfcd26510bea67e64f3e6898b7c58300dc14338254268cade103',
                ]
            )
        );

        $result = $this->crypto->naclSignDetached($unsigned, $secret);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclBoxKeypair
     */
    public function testNaclBoxKeypairWithSuccessResult(): void
    {
        $result = $this->crypto->naclBoxKeypair();
        $keyPair = $result->getKeyPair();

        self::assertEquals(64, strlen($keyPair->getPublic()));
        self::assertEquals(64, strlen($keyPair->getSecret()));
    }

    /**
     * @covers ::naclBoxKeypairFromSecretKey
     */
    public function testNaclBoxKeypairFromSecretKeyWithSuccessResult(): void
    {
        $secret = 'e207b5966fb2c5be1b71ed94ea813202706ab84253bdf4dc55232f82a1caf0d4';

        $expected = new ResultOfGenerateSignKeys(
            new Response(
                [
                    'public' => 'a53b003d3ffc1e159355cb37332d67fc235a7feb6381e36c803274074dc3933a',
                    'secret' => 'e207b5966fb2c5be1b71ed94ea813202706ab84253bdf4dc55232f82a1caf0d4',
                ]
            )
        );
        $result = $this->crypto->naclBoxKeypairFromSecretKey($secret);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclBox
     */
    public function testNaclBoxWithSuccessResult(): void
    {
        $decrypted = base64_encode('Test Message');
        $nonce = 'cd7f99924bf422544046e83595dd5803f17536f5c9a11746';
        $theirPublic = 'c4e2d9fe6a6baf8d1812b799856ef2a306291be7a7024837ad33a8530db79c6b';
        $secret = 'd9b9dc5033fb416134e5d2107fdbacab5aadb297cb82dbdcd137d663bac59f7f';

        $expected = new ResultOfNaclBox(
            new Response(
                [
                    'encrypted' => 'li4XED4kx/pjQ2qdP0eR2d/K30uN94voNADxwA==',
                ]
            )
        );
        $result = $this->crypto->naclBox($decrypted, $nonce, $theirPublic, $secret);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclBoxOpen
     */
    public function testNaclBoxOpenWithSuccessResult(): void
    {
        $encrypted = 'li4XED4kx/pjQ2qdP0eR2d/K30uN94voNADxwA==';
        $nonce = 'cd7f99924bf422544046e83595dd5803f17536f5c9a11746';
        $theirPublic = 'c4e2d9fe6a6baf8d1812b799856ef2a306291be7a7024837ad33a8530db79c6b';
        $secret = 'd9b9dc5033fb416134e5d2107fdbacab5aadb297cb82dbdcd137d663bac59f7f';

        $expected = new ResultOfNaclBoxOpen(
            new Response(
                [
                    'decrypted' => base64_encode('Test Message'),
                ]
            )
        );
        $result = $this->crypto->naclBoxOpen($encrypted, $nonce, $theirPublic, $secret);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclSecretBox
     */
    public function testNaclSecretBoxWithSuccessResult(): void
    {
        $decrypted = base64_encode('Test Message');
        $nonce = '2a33564717595ebe53d91a785b9e068aba625c8453a76e45';
        $key = '8f68445b4e78c000fe4d6b7fc826879c1e63e3118379219a754ae66327764bd8';

        $expected = new ResultOfNaclBox(
            new Response(
                [
                    'encrypted' => 'JL7ejKWe2KXmrsns41yfXoQF0t/C1Q8RGyzQ2A==',
                ]
            )
        );
        $result = $this->crypto->naclSecretBox($decrypted, $nonce, $key);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::naclSecretBoxOpen
     */
    public function testNaclSecretBoxOpenWithSuccessResult(): void
    {
        $encrypted = 'JL7ejKWe2KXmrsns41yfXoQF0t/C1Q8RGyzQ2A==';
        $nonce = '2a33564717595ebe53d91a785b9e068aba625c8453a76e45';
        $key = '8f68445b4e78c000fe4d6b7fc826879c1e63e3118379219a754ae66327764bd8';

        $expected = new ResultOfNaclBoxOpen(
            new Response(
                [
                    'decrypted' => base64_encode('Test Message'),
                ]
            )
        );
        $result = $this->crypto->naclSecretBoxOpen($encrypted, $nonce, $key);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::mnemonicWords
     */
    public function testMnemonicWordsWithSuccessResult(): void
    {
        $dictionary = 1;

        $result = $this->crypto->mnemonicWords($dictionary);
        $wordsNumber = count(explode(' ', $result->getWords()));

        self::assertEquals(2048, $wordsNumber);
    }

    /**
     * @covers ::mnemonicFromRandom
     */
    public function testMnemonicFromRandomWithSuccessResult(): void
    {
        $dictionary = 1;
        $wordCount = 18;

        $result = $this->crypto->mnemonicFromRandom($dictionary, $wordCount);
        $wordsNumber = count(explode(' ', $result->getPhrase()));

        self::assertEquals($wordCount, $wordsNumber);
    }

    /**
     * @covers ::mnemonicFromEntropy
     */
    public function testMnemonicFromEntropyWithSuccessResult(): void
    {
        $entropy = '00112233445566778899AABBCCDDEEFF';
        $dictionary = 1;
        $wordCount = 12;

        $expected = new ResultOfGenerateMnemonic(
            new Response(
                [
                    'phrase' => 'abandon math mimic master filter design carbon crystal rookie group knife young',
                ]
            )
        );
        $result = $this->crypto->mnemonicFromEntropy($entropy, $dictionary, $wordCount);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::mnemonicVerify
     */
    public function testMnemonicVerifyWithSuccessResult(): void
    {
        $phrase = 'abandon math mimic master filter design carbon crystal rookie group knife young';
        $dictionary = 1;
        $wordCount = 12;

        $expected = new ResultOfMnemonicVerify(
            new Response(
                [
                    'valid' => true,
                ]
            )
        );
        $result = $this->crypto->mnemonicVerify($phrase, $dictionary, $wordCount);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::mnemonicDeriveSignKeys
     */
    public function testMnemonicDeriveSignKeysWithSuccessResult(): void
    {
        $phrase = 'abandon math mimic master filter design carbon crystal rookie group knife young';

        $expected = new ResultOfGenerateSignKeys(
            new Response(
                [
                    'public' => '61c3c5b97a33c9c0a03af112fbb27e3f44d99e1f804853f9842bb7a6e5de3ff9',
                    'secret' => '832410564fbe7b1301cf48dc83cbb8a3008d3cf29e05b7457086d4de041030ea',
                ]
            )
        );
        $result = $this->crypto->mnemonicDeriveSignKeys($phrase);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::hdkeyXprvFromMnemonic
     */
    public function testHdkeyXprvFromMnemonicWithSuccessResult(): void
    {
        $phrase = 'abuse boss fly battle rubber wasp afraid hamster guide essence vibrant tattoo';
        $dictionary = 1;
        $wordCount = 12;

        $expected = new ResultOfHDKeyXPrv(
            new Response(
                [
                    'xprv' => 'xprv9s21ZrQH143K25JhKqEwvJW7QAiVvkmi4WRenBZanA6kxHKtKAQQKwZG65kCyW5jWJ8NY9e3GkRoistUjjcpHNsGBUv94istDPXvqGNuWpC',
                ]
            )
        );
        $result = $this->crypto->hdkeyXprvFromMnemonic($phrase, $dictionary, $wordCount);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::hdkeyDeriveFromXprv
     */
    public function testHdkeyDeriveFromXprvWithSuccessResult(): void
    {
        $xprv = 'xprv9s21ZrQH143K25JhKqEwvJW7QAiVvkmi4WRenBZanA6kxHKtKAQQKwZG65kCyW5jWJ8NY9e3GkRoistUjjcpHNsGBUv94istDPXvqGNuWpC';
        $childIndex = 0;
        $hardened = false;

        $expected = new ResultOfHDKeyXPrv(
            new Response(
                [
                    'xprv' => 'xprv9uZwtSeoKf1swgAkVVCEUmC2at6t7MCJoHnBbn1MWJZyxQ4cySkVXPyNh7zjf9VjsP4vEHDDD2a6R35cHubg4WpzXRzniYiy8aJh1gNnBKv',
                ]
            )
        );
        $result = $this->crypto->hdkeyDeriveFromXprv($xprv, $childIndex, $hardened);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::hdkeyDeriveFromXprvPath
     */
    public function testHdkeyDeriveFromXprvPathWithSuccessResult(): void
    {
        $xprv = 'xprv9s21ZrQH143K25JhKqEwvJW7QAiVvkmi4WRenBZanA6kxHKtKAQQKwZG65kCyW5jWJ8NY9e3GkRoistUjjcpHNsGBUv94istDPXvqGNuWpC';
        $path = 'm/44\'/60\'/0\'/0\'';

        $expected = new ResultOfHDKeyXPrv(
            new Response(
                [
                    'xprv' => 'xprvA1KNMo63UcGjmDF1bX39Cw2BXGUwrwMjeD5qvQ3tA3qS3mZQkGtpf4DHq8FDLKAvAjXsYGLHDP2dVzLu9ycta8PXLuSYib2T3vzLf3brVgZ',
                ]
            )
        );
        $result = $this->crypto->hdkeyDeriveFromXprvPath($xprv, $path);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::hdkeySecretFromXprv
     */
    public function testHdkeySecretFromXprvWithSuccessResult(): void
    {
        $xprv = 'xprvA1KNMo63UcGjmDF1bX39Cw2BXGUwrwMjeD5qvQ3tA3qS3mZQkGtpf4DHq8FDLKAvAjXsYGLHDP2dVzLu9ycta8PXLuSYib2T3vzLf3brVgZ';

        $expected = new ResultOfHDKeySecretFromXPrv(
            new Response(
                [
                    'secret' => '1c566ade41169763b155761406d3cef08b29b31cf8014f51be08c0cb4e67c5e1',
                ]
            )
        );
        $result = $this->crypto->hdkeySecretFromXprv($xprv);

        self::assertEquals($expected, $result);
    }

    /**
     * @covers ::hdkeyPublicFromXprv
     */
    public function testHdkeyPublicFromXprvWithSuccessResult(): void
    {
        $xprv = 'xprvA1KNMo63UcGjmDF1bX39Cw2BXGUwrwMjeD5qvQ3tA3qS3mZQkGtpf4DHq8FDLKAvAjXsYGLHDP2dVzLu9ycta8PXLuSYib2T3vzLf3brVgZ';

        $expected = new ResultOfHDKeyPublicFromXPrv(
            new Response(
                [
                    'public' => '02a87d9764eedaacee45b0f777b5a242939b05fa06873bf511ca9a59cb46a5f526',
                ]
            )
        );
        $result = $this->crypto->hdkeyPublicFromXprv($xprv);

        self::assertEquals($expected, $result);
    }
}
