<?php

namespace NovaFrame\Encryption;

use NovaFrame\Encryption\Exceptions\EncryptionException;
use NovaFrame\Encryption\Exceptions\InvalidEncodedString;

class Encryption
{
    /**
     * Cipher method used for encryption/decryption.
     */
    protected const CIPHER = 'aes-256-cbc';

    /**
     * Encrypt data using AES-256-CBC and serialize it.
     *
     * @param mixed $data The data to encrypt (any serializable PHP value).
     * @param string|null $key Optional encryption key; falls back to `app.key` config.
     * @return string Base64 encoded string containing IV + encrypted data.
     * @throws EncryptionException If encryption fails.
     */
    public static function encrypt(mixed $data, ?string $key = null): string
    {
        $key = $key ?? config('app.key');
        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = random_bytes($ivLength);

        $encrypted = openssl_encrypt(
            serialize($data),
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($encrypted === false) {
            throw new EncryptionException('Encryption failed.');
        }

        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt a base64 encoded string encrypted by this class.
     *
     * @param string $encoded The base64 encoded string (IV + encrypted data).
     * @param string|null $key Optional encryption key; falls back to `app.key` config.
     * @return mixed The decrypted original PHP data.
     * @throws InvalidEncodedString If the string cannot be base64 decoded.
     * @throws EncryptionException If decryption fails.
     */
    public static function decrypt(string $encoded, ?string $key = null): mixed
    {
        $key = $key ?? config('app.key');
        $decoded = base64_decode($encoded, true);

        if ($decoded === false) {
            throw new InvalidEncodedString();
        }

        $ivLength = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($decoded, 0, $ivLength);
        $encrypted = substr($decoded, $ivLength);

        $decrypted = openssl_decrypt(
            $encrypted,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted === false) {
            throw new EncryptionException('Decryption failed');
        }

        return unserialize($decrypted);
    }
}
