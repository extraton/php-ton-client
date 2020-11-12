<?php

declare(strict_types=1);

namespace Tests\Unit\Extraton\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfConvertPublicKeyToTonSafeFormat;
use Extraton\TonClient\Entity\Crypto\ResultOfFactorize;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateMnemonic;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateRandomBytes;
use Extraton\TonClient\Entity\Crypto\ResultOfGenerateSignKeys;
use Extraton\TonClient\Entity\Crypto\ResultOfHash;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeyPublicFromXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeySecretFromXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfHDKeyXPrv;
use Extraton\TonClient\Entity\Crypto\ResultOfMnemonicVerify;
use Extraton\TonClient\Entity\Crypto\ResultOfMnemonicWords;
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

use function microtime;
use function uniqid;
use function hexdec;
use function random_int;

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

    /**
     * @covers ::naclSignOpen
     */
    public function testNaclSignOpenWithSuccessResult(): void
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
                'crypto.nacl_sign_open',
                [
                    'signed' => $signed,
                    'public' => $public,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSignOpen($result);

        self::assertEquals($expected, $this->crypto->naclSignOpen($signed, $public));
    }

    /**
     * @covers ::naclSignDetached
     */
    public function testNaclSignDetachedWithSuccessResult(): void
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
                'crypto.nacl_sign_detached',
                [
                    'unsigned' => $unsigned,
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSignDetached($result);

        self::assertEquals($expected, $this->crypto->naclSignDetached($unsigned, $secret));
    }

    /**
     * @covers ::naclBoxKeypair
     */
    public function testNaclBoxKeypairWithSuccessResult(): void
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
                'crypto.nacl_box_keypair',
                []
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateSignKeys($result);

        self::assertEquals($expected, $this->crypto->naclBoxKeypair());
    }

    /**
     * @covers ::naclBoxKeypairFromSecretKey
     */
    public function testNaclBoxKeypairFromSecretKeyWithSuccessResult(): void
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
                'crypto.nacl_box_keypair_from_secret_key',
                [
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateSignKeys($result);

        self::assertEquals($expected, $this->crypto->naclBoxKeypairFromSecretKey($secret));
    }

    /**
     * @covers ::naclBox
     */
    public function testNaclBoxWithSuccessResult(): void
    {
        $decrypted = uniqid(microtime(), true);
        $nonce = uniqid(microtime(), true);
        $theirPublic = uniqid(microtime(), true);
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
                'crypto.nacl_box',
                [
                    'decrypted' => $decrypted,
                    'nonce' => $nonce,
                    'their_public' => $theirPublic,
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBox($result);

        self::assertEquals($expected, $this->crypto->naclBox($decrypted, $nonce, $theirPublic, $secret));
    }

    /**
     * @covers ::naclBoxOpen
     */
    public function testNaclBoxOpenWithSuccessResult(): void
    {
        $encrypted = uniqid(microtime(), true);
        $nonce = uniqid(microtime(), true);
        $theirPublic = uniqid(microtime(), true);
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
                'crypto.nacl_box_open',
                [
                    'encrypted' => $encrypted,
                    'nonce' => $nonce,
                    'their_public' => $theirPublic,
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBoxOpen($result);

        self::assertEquals($expected, $this->crypto->naclBoxOpen($encrypted, $nonce, $theirPublic, $secret));
    }

    /**
     * @covers ::naclSecretBox
     */
    public function testNaclSecretBoxWithSuccessResult(): void
    {
        $decrypted = uniqid(microtime(), true);
        $nonce = uniqid(microtime(), true);
        $key = uniqid(microtime(), true);
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
                'crypto.nacl_secret_box',
                [
                    'decrypted' => $decrypted,
                    'nonce' => $nonce,
                    'key' => $key,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBox($result);

        self::assertEquals($expected, $this->crypto->naclSecretBox($decrypted, $nonce, $key));
    }

    /**
     * @covers ::naclSecretBoxOpen
     */
    public function testNaclSecretBoxOpenWithSuccessResult(): void
    {
        $encrypted = uniqid(microtime(), true);
        $nonce = uniqid(microtime(), true);
        $key = uniqid(microtime(), true);
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
                'crypto.nacl_secret_box_open',
                [
                    'encrypted' => $encrypted,
                    'nonce' => $nonce,
                    'key' => $key,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBoxOpen($result);

        self::assertEquals($expected, $this->crypto->naclSecretBoxOpen($encrypted, $nonce, $key));
    }

    /**
     * @covers ::mnemonicWords
     */
    public function testMnemonicWordsWithSuccessResult(): void
    {
        $dictionary = hexdec(uniqid());
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
                'crypto.mnemonic_words',
                [
                    'dictionary' => $dictionary,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfMnemonicWords($result);

        self::assertEquals($expected, $this->crypto->mnemonicWords($dictionary));
    }

    /**
     * @covers ::mnemonicFromRandom
     */
    public function testMnemonicFromRandomWithSuccessResult(): void
    {
        $dictionary = hexdec(uniqid());
        $wordCount = hexdec(uniqid());
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
                'crypto.mnemonic_from_random',
                [
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateMnemonic($result);

        self::assertEquals($expected, $this->crypto->mnemonicFromRandom($dictionary, $wordCount));
    }

    /**
     * @covers ::mnemonicFromEntropy
     */
    public function testMnemonicFromEntropyWithSuccessResult(): void
    {
        $entropy = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid());
        $wordCount = hexdec(uniqid());
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
                'crypto.mnemonic_from_entropy',
                [
                    'entropy' => $entropy,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateMnemonic($result);

        self::assertEquals($expected, $this->crypto->mnemonicFromEntropy($entropy, $dictionary, $wordCount));
    }

    /**
     * @covers ::mnemonicVerify
     */
    public function testMnemonicVerifyWithSuccessResult(): void
    {
        $phrase = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid());
        $wordCount = hexdec(uniqid());
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
                'crypto.mnemonic_verify',
                [
                    'phrase' => $phrase,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfMnemonicVerify($result);

        self::assertEquals($expected, $this->crypto->mnemonicVerify($phrase, $dictionary, $wordCount));
    }

    /**
     * @covers ::mnemonicDeriveSignKeys
     */
    public function testMnemonicDeriveSignKeysWithSuccessResult(): void
    {
        $phrase = uniqid(microtime(), true);
        $path = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid());
        $wordCount = hexdec(uniqid());
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
                'crypto.mnemonic_derive_sign_keys',
                [
                    'phrase' => $phrase,
                    'path' => $path,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGenerateSignKeys($result);

        self::assertEquals($expected, $this->crypto->mnemonicDeriveSignKeys($phrase, $path, $dictionary, $wordCount));
    }

    /**
     * @covers ::hdkeyXprvFromMnemonic
     */
    public function testHdkeyXprvFromMnemonicWithSuccessResult(): void
    {
        $phrase = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid());
        $wordCount = hexdec(uniqid());
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
                'crypto.hdkey_xprv_from_mnemonic',
                [
                    'phrase' => $phrase,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeyXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeyXprvFromMnemonic($phrase, $dictionary, $wordCount));
    }

    /**
     * @covers ::hdkeyDeriveFromXprv
     */
    public function testHdkeyDeriveFromXprvWithSuccessResult(): void
    {
        $xprv = uniqid(microtime(), true);
        $childIndex = hexdec(uniqid());
        $hardened = (bool)random_int(0, 1);
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
                'crypto.hdkey_derive_from_xprv',
                [
                    'xprv' => $xprv,
                    'child_index' => $childIndex,
                    'hardened' => $hardened,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeyXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeyDeriveFromXprv($xprv, $childIndex, $hardened));
    }

    /**
     * @covers ::hdkeyDeriveFromXprvPath
     */
    public function testHdkeyDeriveFromXprvPathWithSuccessResult(): void
    {
        $xprv = uniqid(microtime(), true);
        $path = uniqid(microtime(), true);
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
                'crypto.hdkey_derive_from_xprv_path',
                [
                    'xprv' => $xprv,
                    'path' => $path,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeyXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeyDeriveFromXprvPath($xprv, $path));
    }

    /**
     * @covers ::hdkeySecretFromXprv
     */
    public function testHdkeySecretFromXprvWithSuccessResult(): void
    {
        $xprv = uniqid(microtime(), true);
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
                'crypto.hdkey_secret_from_xprv',
                [
                    'xprv' => $xprv,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeySecretFromXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeySecretFromXprv($xprv));
    }

    /**
     * @covers ::hdkeyPublicFromXprv
     */
    public function testHdkeyPublicFromXprvWithSuccessResult(): void
    {
        $xprv = uniqid(microtime(), true);
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
                'crypto.hdkey_public_from_xprv',
                [
                    'xprv' => $xprv,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeyPublicFromXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeyPublicFromXprv($xprv));
    }
}
