<?php

if (!function_exists('csrf')) {
    /**
     * Generates a CSRF token.
     *
     * @return string The encrypted CSRF token.
     */
    function csrf(): string
    {
        return bin2hex(random_bytes(32));
    }
}

if (!function_exists('encrypt')) {
    /**
     * Encrypts data using AES-256-CBC encryption.
     *
     * @param mixed $data The data to be encrypted.
     * @return string The encrypted data encoded in base64 format.
     */
    function encrypt(mixed $data): string
    {
        $cipher = 'aes-256-cbc';
        $iv_length = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $encrypted = openssl_encrypt(serialize($data), $cipher, config('app.key'), 0, $iv);

        return base64_encode($iv . $encrypted);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypts data that was encrypted using the encrypt function.
     *
     * @param string $data The encrypted data.
     * @return mixed The decrypted data.
     */
    function decrypt(string $data): mixed
    {
        $cipher = 'aes-256-cbc';
        $iv_length = openssl_cipher_iv_length($cipher);
        $iv = substr(base64_decode($data), 0, $iv_length);

        $encrypted = substr(base64_decode($data), $iv_length);

        return unserialize(openssl_decrypt($encrypted, $cipher, config('app.key'), 0, $iv));
    }
}
