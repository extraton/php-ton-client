<?php

declare(strict_types=1);

namespace Extraton\Tests\Unit\TonClient;

use Extraton\TonClient\Crypto;
use Extraton\TonClient\Entity\Crypto\KeyPair;
use Extraton\TonClient\Entity\Crypto\ResultOfGetSigningBox;
use Extraton\TonClient\Entity\Crypto\ResultOfNaclSignDetachedVerify;
use Extraton\TonClient\Entity\Crypto\ResultOfChaCha20;
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
use Extraton\TonClient\Entity\Crypto\ResultOfSigningBoxGetPublicKey;
use Extraton\TonClient\Entity\Crypto\ResultOfSigningBoxSign;
use Extraton\TonClient\Entity\Crypto\ResultOfTonCrc16;
use Extraton\TonClient\Entity\Crypto\ResultOfVerifySignature;
use Extraton\TonClient\Handler\Response;

use function hexdec;
use function microtime;
use function random_int;
use function uniqid;

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
    public function testFactorize(): void
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
    public function testModularPower(): void
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
                    'base'     => $base,
                    'exponent' => $exponent,
                    'modulus'  => $modulus,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfModularPower($result);

        self::assertEquals($expected, $this->crypto->modularPower($base, $exponent, $modulus));
    }

    /**
     * @covers ::tonCrc16
     */
    public function testTonCrc16(): void
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
    public function testGenerateRandomBytes(): void
    {
        $length = hexdec(uniqid('', false));
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
    public function testConvertPublicKeyToTonSafeFormat(): void
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
    public function testGenerateRandomSignKeys(): void
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
    public function testSign(): void
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
                    'keys'     => $keyPair,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSign($result);

        self::assertEquals($expected, $this->crypto->sign($unsigned, $keyPair));
    }

    /**
     * @covers ::verifySignature
     */
    public function testVerifySignature(): void
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
    public function testSha256(): void
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
    public function testSha512(): void
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
    public function testScrypt(): void
    {
        $password = uniqid(microtime(), true);
        $salt = uniqid(microtime(), true);
        $logN = hexdec(uniqid('', false));
        $r = hexdec(uniqid('', false));
        $p = hexdec(uniqid('', false));
        $dkLen = hexdec(uniqid('', false));
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
                    'salt'     => $salt,
                    'log_n'    => $logN,
                    'r'        => $r,
                    'p'        => $p,
                    'dk_len'   => $dkLen,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfScrypt($result);

        self::assertEquals($expected, $this->crypto->scrypt($password, $salt, $logN, $r, $p, $dkLen));
    }

    /**
     * @covers ::naclSignKeyPairFromSecretKey
     */
    public function testNaclSignKeyPairFromSecretKey(): void
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
    public function testNaclSign(): void
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
                    'secret'   => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSign($result);

        self::assertEquals($expected, $this->crypto->naclSign($unsigned, $secret));
    }

    /**
     * @covers ::naclSignOpen
     */
    public function testNaclSignOpen(): void
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
    public function testNaclSignDetached(): void
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
                    'secret'   => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSignDetached($result);

        self::assertEquals($expected, $this->crypto->naclSignDetached($unsigned, $secret));
    }

    /**
     * @covers ::naclBoxKeypair
     */
    public function testNaclBoxKeypair(): void
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
    public function testNaclBoxKeypairFromSecretKey(): void
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
    public function testNaclBox(): void
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
                    'decrypted'    => $decrypted,
                    'nonce'        => $nonce,
                    'their_public' => $theirPublic,
                    'secret'       => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBox($result);

        self::assertEquals($expected, $this->crypto->naclBox($decrypted, $nonce, $theirPublic, $secret));
    }

    /**
     * @covers ::naclBoxOpen
     */
    public function testNaclBoxOpen(): void
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
                    'encrypted'    => $encrypted,
                    'nonce'        => $nonce,
                    'their_public' => $theirPublic,
                    'secret'       => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBoxOpen($result);

        self::assertEquals($expected, $this->crypto->naclBoxOpen($encrypted, $nonce, $theirPublic, $secret));
    }

    /**
     * @covers ::naclSecretBox
     */
    public function testNaclSecretBox(): void
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
                    'nonce'     => $nonce,
                    'key'       => $key,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBox($result);

        self::assertEquals($expected, $this->crypto->naclSecretBox($decrypted, $nonce, $key));
    }

    /**
     * @covers ::naclSecretBoxOpen
     */
    public function testNaclSecretBoxOpen(): void
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
                    'nonce'     => $nonce,
                    'key'       => $key,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclBoxOpen($result);

        self::assertEquals($expected, $this->crypto->naclSecretBoxOpen($encrypted, $nonce, $key));
    }

    /**
     * @covers ::mnemonicWords
     */
    public function testMnemonicWords(): void
    {
        $dictionary = hexdec(uniqid('', false));
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
    public function testMnemonicFromRandom(): void
    {
        $dictionary = hexdec(uniqid('', false));
        $wordCount = hexdec(uniqid('', false));
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
    public function testMnemonicFromEntropy(): void
    {
        $entropy = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid('', false));
        $wordCount = hexdec(uniqid('', false));
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
                    'entropy'    => $entropy,
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
    public function testMnemonicVerify(): void
    {
        $phrase = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid('', false));
        $wordCount = hexdec(uniqid('', false));
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
                    'phrase'     => $phrase,
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
    public function testMnemonicDeriveSignKeys(): void
    {
        $phrase = uniqid(microtime(), true);
        $path = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid('', false));
        $wordCount = hexdec(uniqid('', false));
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
                    'phrase'     => $phrase,
                    'path'       => $path,
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
    public function testHdkeyXprvFromMnemonic(): void
    {
        $phrase = uniqid(microtime(), true);
        $dictionary = hexdec(uniqid('', false));
        $wordCount = hexdec(uniqid('', false));
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
                    'phrase'     => $phrase,
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
    public function testHdkeyDeriveFromXprv(): void
    {
        $xprv = uniqid(microtime(), true);
        $childIndex = hexdec(uniqid('', false));
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
                    'xprv'        => $xprv,
                    'child_index' => $childIndex,
                    'hardened'    => $hardened,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfHDKeyXPrv($result);

        self::assertEquals($expected, $this->crypto->hdkeyDeriveFromXprv($xprv, $childIndex, $hardened));
    }

    /**
     * @covers ::hdkeyDeriveFromXprvPath
     */
    public function testHdkeyDeriveFromXprvPath(): void
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
    public function testHdkeySecretFromXprv(): void
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
    public function testHdkeyPublicFromXprv(): void
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

    /**
     * @covers ::chaCha20
     */
    public function testChaCha20(): void
    {
        $data = uniqid(microtime(), true);
        $key = uniqid(microtime(), true);
        $nonce = uniqid(microtime(), true);
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
                'crypto.chacha20',
                [
                    'data'  => $data,
                    'key'   => $key,
                    'nonce' => $nonce,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfChaCha20($result);

        self::assertEquals($expected, $this->crypto->chaCha20($data, $key, $nonce));
    }

    /**
     * @covers ::naclSignDetachedVerify
     */
    public function testNaclSignDetachedVerify(): void
    {
        $unsigned = uniqid(microtime(), true);
        $signature = uniqid(microtime(), true);
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
                'crypto.nacl_sign_detached_verify',
                [
                    'unsigned'  => $unsigned,
                    'signature' => $signature,
                    'public'    => $public,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfNaclSignDetachedVerify($result);

        self::assertEquals(
            $expected,
            $this->crypto->naclSignDetachedVerify(
                $unsigned,
                $signature,
                $public
            )
        );
    }

    /**
     * @covers ::getSigningBox
     */
    public function testGetSigningBox(): void
    {
        $public = uniqid(microtime(), true);
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
                'crypto.get_signing_box',
                [
                    'public' => $public,
                    'secret' => $secret,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfGetSigningBox($result);

        self::assertEquals(
            $expected,
            $this->crypto->getSigningBox(
                $public,
                $secret
            )
        );
    }

    /**
     * @covers ::signingBoxGetPublicKey
     */
    public function testSigningBoxGetPublicKey(): void
    {
        $handle = time();

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
                'crypto.signing_box_get_public_key',
                [
                    'handle' => $handle,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSigningBoxGetPublicKey($result);

        self::assertEquals(
            $expected,
            $this->crypto->signingBoxGetPublicKey($handle)
        );
    }

    /**
     * @covers ::signingBoxSign
     */
    public function testSigningBoxSign(): void
    {
        $handle = time();
        $unsigned = uniqid(microtime(), true);

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
                'crypto.signing_box_sign',
                [
                    'signing_box' => $handle,
                    'unsigned'    => $unsigned,
                ]
            )
            ->willReturn($this->mockPromise);

        $expected = new ResultOfSigningBoxSign($result);

        self::assertEquals(
            $expected,
            $this->crypto->signingBoxSign($handle, $unsigned)
        );
    }

    /**
     * @covers ::removeSigningBox
     */
    public function testRemoveSigningBox(): void
    {
        $handle = time();

        $this->mockPromise->expects(self::once())
            ->method('wait')
            ->with();

        $this->mockTonClient->expects(self::once())
            ->method('request')
            ->with(
                'crypto.remove_signing_box',
                [
                    'handle' => $handle,
                ]
            )
            ->willReturn($this->mockPromise);

        $this->crypto->removeSigningBox($handle);
    }
}
