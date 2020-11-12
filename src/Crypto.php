<?php

declare(strict_types=1);

namespace Extraton\TonClient;

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

/**
 * Crypto module
 */
class Crypto
{
    private TonClient $tonClient;

    /**
     * @param TonClient $tonClient
     */
    public function __construct(TonClient $tonClient)
    {
        $this->tonClient = $tonClient;
    }

    /**
     * Performs prime factorization
     *
     * Decomposition of a composite number
     * into a product of smaller prime integers (factors).
     * See https://en.wikipedia.org/wiki/Integer_factorization
     *
     * @param string $composite Hexadecimal representation of u64 composite number
     * @return ResultOfFactorize
     */
    public function factorize(string $composite): ResultOfFactorize
    {
        return new ResultOfFactorize(
            $this->tonClient->request(
                'crypto.factorize',
                [
                    'composite' => $composite,
                ]
            )->wait()
        );
    }

    /**
     * Performs modular exponentiation for big integers (base^exponent mod modulus).
     *
     * See https://en.wikipedia.org/wiki/Modular_exponentiation
     *
     * @param string $base base argument of calculation
     * @param string $exponent exponent argument of calculation
     * @param string $modulus modulus argument of calculation
     * @return ResultOfModularPower
     */
    public function modularPower(string $base, string $exponent, string $modulus): ResultOfModularPower
    {
        return new ResultOfModularPower(
            $this->tonClient->request(
                'crypto.modular_power',
                [
                    'base' => $base,
                    'exponent' => $exponent,
                    'modulus' => $modulus,
                ]
            )->wait()
        );
    }

    /**
     * Calculates CRC16 using TON algorithm.
     *
     * @param string $data Input data for CRC calculation. Encoded with base64
     * @return ResultOfTonCrc16
     */
    public function tonCrc16(string $data): ResultOfTonCrc16
    {
        return new ResultOfTonCrc16(
            $this->tonClient->request(
                'crypto.ton_crc16',
                [
                    'data' => $data,
                ]
            )->wait()
        );
    }

    /**
     * Generates random byte array of the specified length and returns it in base64 format.
     *
     * @param int $length Size of random byte array
     * @return ResultOfGenerateRandomBytes
     */
    public function generateRandomBytes(int $length): ResultOfGenerateRandomBytes
    {
        return new ResultOfGenerateRandomBytes(
            $this->tonClient->request(
                'crypto.generate_random_bytes',
                [
                    'length' => $length,
                ]
            )->wait()
        );
    }

    /**
     * Converts public key to ton safe_format.
     *
     * @param string $publicKey Public key - 64 symbols hex string
     * @return ResultOfConvertPublicKeyToTonSafeFormat
     */
    public function convertPublicKeyToTonSafeFormat(string $publicKey): ResultOfConvertPublicKeyToTonSafeFormat
    {
        return new ResultOfConvertPublicKeyToTonSafeFormat(
            $this->tonClient->request(
                'crypto.convert_public_key_to_ton_safe_format',
                [
                    'public_key' => $publicKey,
                ]
            )->wait()
        );
    }

    /**
     * Generates random ed25519 key pair.
     *
     * @return ResultOfGenerateSignKeys
     */
    public function generateRandomSignKeys(): ResultOfGenerateSignKeys
    {
        return new ResultOfGenerateSignKeys(
            $this->tonClient->request('crypto.generate_random_sign_keys',)->wait()
        );
    }

    /**
     * Signs a data using the provided keys.
     *
     * @param string $unsigned Data that must be signed encoded in base64
     * @param KeyPair $keyPair Sign keys
     * @return ResultOfSign
     */
    public function sign(string $unsigned, KeyPair $keyPair): ResultOfSign
    {
        return new ResultOfSign(
            $this->tonClient->request(
                'crypto.sign',
                [
                    'unsigned' => $unsigned,
                    'keys' => $keyPair,
                ]
            )->wait()
        );
    }

    /**
     * Verifies signed data using the provided public key. Raises error if verification is failed.
     *
     * @param string $signed Signed data that must be verified encoded in base64
     * @param string $public Signer's public key - 64 symbols hex string
     * @return ResultOfVerifySignature
     */
    public function verifySignature(string $signed, string $public): ResultOfVerifySignature
    {
        return new ResultOfVerifySignature(
            $this->tonClient->request(
                'crypto.verify_signature',
                [
                    'signed' => $signed,
                    'public' => $public,
                ]
            )->wait()
        );
    }

    /**
     * Calculates SHA256 hash of the specified data.
     *
     * @param string $data Input data for hash calculation. Encoded with base64
     * @return ResultOfHash
     */
    public function sha256(string $data): ResultOfHash
    {
        return new ResultOfHash(
            $this->tonClient->request(
                'crypto.sha256',
                [
                    'data' => $data,
                ]
            )->wait()
        );
    }

    /**
     * Calculates SHA512 hash of the specified data.
     *
     * @param string $data Input data for hash calculation. Encoded with base64
     * @return ResultOfHash
     */
    public function sha512(string $data): ResultOfHash
    {
        return new ResultOfHash(
            $this->tonClient->request(
                'crypto.sha512',
                [
                    'data' => $data,
                ]
            )->wait()
        );
    }

    /**
     * Derives key from password and key using scrypt algorithm.
     * See [https://en.wikipedia.org/wiki/Scrypt].
     *
     * @param string $password The password bytes to be hashed. Must be encoded with base64.
     * @param string $salt A salt bytes that modifies the hash to protect against Rainbow table attacks.
     *                     Must be encoded with base64.
     * @param int $logN CPU/memory cost parameter.
     *                  Must be less than 64.
     * @param int $r The block size parameter, which fine-tunes sequential memory read size and performance.
     *               Must be greater than 0 and less than or equal to 4294967295.
     * @param int $p Parallelization parameter.
     *               Must be greater than 0 and less than 4294967295.
     * @param int $dkLen Intended output length in octets of the derived key.
     * @return ResultOfScrypt
     */
    public function scrypt(string $password, string $salt, int $logN, int $r, int $p, int $dkLen): ResultOfScrypt
    {
        return new ResultOfScrypt(
            $this->tonClient->request(
                'crypto.scrypt',
                [
                    'password' => $password,
                    'salt' => $salt,
                    'log_n' => $logN,
                    'r' => $r,
                    'p' => $p,
                    'dk_len' => $dkLen,
                ]
            )->wait()
        );
    }

    /**
     * Generates a key pair for signing from the secret key.
     *
     * @param string $secret Secret key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfGenerateSignKeys
     */
    public function naclSignKeyPairFromSecretKey(string $secret): ResultOfGenerateSignKeys
    {
        return new ResultOfGenerateSignKeys(
            $this->tonClient->request(
                'crypto.nacl_sign_keypair_from_secret_key',
                [
                    'secret' => $secret,
                ]
            )->wait()
        );
    }

    /**
     * Signs data using the signer's secret key.
     *
     * @param string $unsigned Data that must be signed encoded in base64
     * @param string $secret Signer's secret key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclSign
     */
    public function naclSign(string $unsigned, string $secret): ResultOfNaclSign
    {
        return new ResultOfNaclSign(
            $this->tonClient->request(
                'crypto.nacl_sign',
                [
                    'unsigned' => $unsigned,
                    'secret' => $secret,
                ]
            )->wait()
        );
    }

    /**
     * Extract unsigned data.
     *
     * @param string $signed Signed data that must be unsigned. Encoded with base64
     * @param string $public Signer's public key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclSignOpen
     */
    public function naclSignOpen(string $signed, string $public): ResultOfNaclSignOpen
    {
        return new ResultOfNaclSignOpen(
            $this->tonClient->request(
                'crypto.nacl_sign_open',
                [
                    'signed' => $signed,
                    'public' => $public,
                ]
            )->wait()
        );
    }

    /**
     * Get signature.
     *
     * @param string $unsigned Data that must be signed encoded in base64
     * @param string $secret Signer's secret key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclSignDetached
     */
    public function naclSignDetached(string $unsigned, string $secret): ResultOfNaclSignDetached
    {
        return new ResultOfNaclSignDetached(
            $this->tonClient->request(
                'crypto.nacl_sign_detached',
                [
                    'unsigned' => $unsigned,
                    'secret' => $secret,
                ]
            )->wait()
        );
    }

    /**
     * Generate keypair.
     *
     * @return ResultOfGenerateSignKeys
     */
    public function naclBoxKeypair(): ResultOfGenerateSignKeys
    {
        return new ResultOfGenerateSignKeys(
            $this->tonClient->request('crypto.nacl_box_keypair')->wait()
        );
    }

    /**
     * Generate keypair from a secret key.
     *
     * @param string $secret Secret key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfGenerateSignKeys
     */
    public function naclBoxKeypairFromSecretKey(string $secret): ResultOfGenerateSignKeys
    {
        return new ResultOfGenerateSignKeys(
            $this->tonClient->request(
                'crypto.nacl_box_keypair_from_secret_key',
                [
                    'secret' => $secret,
                ],
            )->wait()
        );
    }

    /**
     * Public key authenticated encryption.
     *
     * Encrypt and authenticate a message using the senders secret key, the recievers public key, and a nonce.
     *
     * @param string $decrypted Data that must be encrypted encoded in base64
     * @param string $nonce Nonce, encoded in hex
     * @param string $theirPublic Receiver's public key - unprefixed 0-padded to 64 symbols hex string
     * @param string $secret Sender's private key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclBox
     */
    public function naclBox(string $decrypted, string $nonce, string $theirPublic, string $secret): ResultOfNaclBox
    {
        return new ResultOfNaclBox(
            $this->tonClient->request(
                'crypto.nacl_box',
                [
                    'decrypted' => $decrypted,
                    'nonce' => $nonce,
                    'their_public' => $theirPublic,
                    'secret' => $secret,
                ],
            )->wait()
        );
    }

    /**
     * Decrypt and verify the cipher text using the recievers secret key, the senders public key, and the nonce.
     *
     * @param string $encrypted Data that must be decrypted. Encoded with base64
     * @param string $nonce Nonce, encoded in hex
     * @param string $theirPublic Receiver's public key - unprefixed 0-padded to 64 symbols hex string
     * @param string $secret Sender's private key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclBoxOpen
     */
    public function naclBoxOpen(string $encrypted, string $nonce, string $theirPublic, string $secret): ResultOfNaclBoxOpen
    {
        return new ResultOfNaclBoxOpen(
            $this->tonClient->request(
                'crypto.nacl_box_open',
                [
                    'encrypted' => $encrypted,
                    'nonce' => $nonce,
                    'their_public' => $theirPublic,
                    'secret' => $secret,
                ],
            )->wait()
        );
    }

    /**
     * Encrypt and authenticate message using nonce and secret key.
     *
     * @param string $decrypted Data that must be encrypted. Encoded with base64
     * @param string $nonce Nonce, encoded in hex
     * @param string $key Secret key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclBox
     */
    public function naclSecretBox(string $decrypted, string $nonce, string $key): ResultOfNaclBox
    {
        return new ResultOfNaclBox(
            $this->tonClient->request(
                'crypto.nacl_secret_box',
                [
                    'decrypted' => $decrypted,
                    'nonce' => $nonce,
                    'key' => $key,
                ],
            )->wait()
        );
    }

    /**
     * Encrypt and authenticate message using nonce and secret key.
     *
     * @param string $encrypted Data that must be decrypted. Encoded with base64
     * @param string $nonce Nonce, encoded in hex
     * @param string $key Public key - unprefixed 0-padded to 64 symbols hex string
     * @return ResultOfNaclBoxOpen
     */
    public function naclSecretBoxOpen(string $encrypted, string $nonce, string $key): ResultOfNaclBoxOpen
    {
        return new ResultOfNaclBoxOpen(
            $this->tonClient->request(
                'crypto.nacl_secret_box_open',
                [
                    'encrypted' => $encrypted,
                    'nonce' => $nonce,
                    'key' => $key,
                ],
            )->wait()
        );
    }

    /**
     * Prints the list of words from the specified dictionary.
     *
     * @param ?int $dictionary Dictionary identifier
     * @return ResultOfMnemonicWords
     */
    public function mnemonicWords(int $dictionary = null): ResultOfMnemonicWords
    {
        return new ResultOfMnemonicWords(
            $this->tonClient->request(
                'crypto.mnemonic_words',
                [
                    'dictionary' => $dictionary,
                ],
            )->wait()
        );
    }

    /**
     * Generates a random mnemonic from the specified dictionary and word count.
     *
     * @param ?int $dictionary Dictionary identifier
     * @param ?int $wordCount Mnemonic word count
     * @return ResultOfGenerateMnemonic
     */
    public function mnemonicFromRandom(int $dictionary = null, int $wordCount = null): ResultOfGenerateMnemonic
    {
        return new ResultOfGenerateMnemonic(
            $this->tonClient->request(
                'crypto.mnemonic_from_random',
                [
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ],
            )->wait()
        );
    }

    /**
     * Generates mnemonic from pre-generated entropy.
     *
     * @param string $entropy Entropy bytes. Hex encoded.
     * @param ?int $dictionary Dictionary identifier
     * @param ?int $wordCount Mnemonic word count
     * @return ResultOfGenerateMnemonic
     */
    public function mnemonicFromEntropy(
        string $entropy,
        int $dictionary = null,
        int $wordCount = null
    ): ResultOfGenerateMnemonic
    {
        return new ResultOfGenerateMnemonic(
            $this->tonClient->request(
                'crypto.mnemonic_from_entropy',
                [
                    'entropy' => $entropy,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ],
            )->wait()
        );
    }

    /**
     * The phrase supplied will be checked for word length and validated according to the checksum specified in BIP0039.
     *
     * @param string $phrase Phrase
     * @param ?int $dictionary Dictionary identifier
     * @param ?int $wordCount Mnemonic word count
     * @return ResultOfMnemonicVerify
     */
    public function mnemonicVerify(
        string $phrase,
        int $dictionary = null,
        int $wordCount = null
    ): ResultOfMnemonicVerify
    {
        return new ResultOfMnemonicVerify(
            $this->tonClient->request(
                'crypto.mnemonic_verify',
                [
                    'phrase' => $phrase,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ],
            )->wait()
        );
    }

    /**
     * Validates the seed phrase, generates master key and then derives the key pair from the master key and the specified path.
     *
     * @param string $phrase Phrase
     * @param ?string $path Derivation path, for instance "m/44'/396'/0'/0/0"
     * @param ?int $dictionary Dictionary identifier
     * @param ?int $wordCount Mnemonic word count
     * @return ResultOfGenerateSignKeys
     */
    public function mnemonicDeriveSignKeys(
        string $phrase,
        string $path = null,
        int $dictionary = null,
        int $wordCount = null
    ): ResultOfGenerateSignKeys
    {
        return new ResultOfGenerateSignKeys(
            $this->tonClient->request(
                'crypto.mnemonic_derive_sign_keys',
                [
                    'phrase' => $phrase,
                    'path' => $path,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ],
            )->wait()
        );
    }

    /**
     * Generates an extended master private key that will be the root for all the derived keys.
     *
     * @param string $phrase Phrase
     * @param ?int $dictionary Dictionary identifier
     * @param ?int $wordCount Mnemonic word count
     * @return ResultOfHDKeyXPrv
     */
    public function hdkeyXprvFromMnemonic(
        string $phrase,
        int $dictionary = null,
        int $wordCount = null
    ): ResultOfHDKeyXPrv
    {
        return new ResultOfHDKeyXPrv(
            $this->tonClient->request(
                'crypto.hdkey_xprv_from_mnemonic',
                [
                    'phrase' => $phrase,
                    'dictionary' => $dictionary,
                    'word_count' => $wordCount,
                ],
            )->wait()
        );
    }

    /**
     * Returns extended private key derived from the specified extended private key and child index.
     *
     * @param string $xprv Serialized extended private key
     * @param int $childIndex Child index (see BIP-0032)
     * @param bool $hardened Indicates the derivation of hardened/not-hardened key (see BIP-0032)
     * @return ResultOfHDKeyXPrv
     */
    public function hdkeyDeriveFromXprv(string $xprv, int $childIndex, bool $hardened): ResultOfHDKeyXPrv
    {
        return new ResultOfHDKeyXPrv(
            $this->tonClient->request(
                'crypto.hdkey_derive_from_xprv',
                [
                    'xprv' => $xprv,
                    'child_index' => $childIndex,
                    'hardened' => $hardened,
                ],
            )->wait()
        );
    }

    /**
     * Derives the exented private key from the specified key and path.
     *
     * @param string $xprv Serialized extended private key
     * @param string $path Derivation path, for instance "m/44'/396'/0'/0/0"
     * @return ResultOfHDKeyXPrv
     */
    public function hdkeyDeriveFromXprvPath(string $xprv, string $path): ResultOfHDKeyXPrv
    {
        return new ResultOfHDKeyXPrv(
            $this->tonClient->request(
                'crypto.hdkey_derive_from_xprv_path',
                [
                    'xprv' => $xprv,
                    'path' => $path,
                ],
            )->wait()
        );
    }

    /**
     * Extracts the private key from the serialized extended private key.
     *
     * @param string $xprv Serialized extended private key
     * @return ResultOfHDKeySecretFromXPrv
     */
    public function hdkeySecretFromXprv(string $xprv): ResultOfHDKeySecretFromXPrv
    {
        return new ResultOfHDKeySecretFromXPrv(
            $this->tonClient->request(
                'crypto.hdkey_secret_from_xprv',
                [
                    'xprv' => $xprv,
                ],
            )->wait()
        );
    }

    /**
     * Extracts the public key from the serialized extended private key.
     *
     * @param string $xprv Serialized extended private key
     * @return ResultOfHDKeyPublicFromXPrv
     */
    public function hdkeyPublicFromXprv(string $xprv): ResultOfHDKeyPublicFromXPrv
    {
        return new ResultOfHDKeyPublicFromXPrv(
            $this->tonClient->request(
                'crypto.hdkey_public_from_xprv',
                [
                    'xprv' => $xprv,
                ],
            )->wait()
        );
    }
}
