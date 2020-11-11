<?php

declare(strict_types=1);

namespace Extraton\TonClient;

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
}
