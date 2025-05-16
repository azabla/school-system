<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Secure {

    protected $key;
    protected $cipher = 'AES-256-CBC';

    public function __construct()
    {
        $CI =& get_instance();
        $this->key = $CI->config->item('encryption_key');
    }

    // Encrypt using OpenSSL
    public function encrypt($data)
    {
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            0,
            $iv
        );

        // Store IV with encrypted data for decryption
        return base64_encode($iv . $encrypted);
    }

    // Decrypt using OpenSSL
    public function decrypt($data)
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            0,
            $iv
        );
    }
}