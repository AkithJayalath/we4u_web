<?php
class EncryptionHelper {
    private static $cipher = "AES-256-CBC";
    private static $key = null; // Will be set from config

    // Initialize with encryption key from config
    public static function init($encryptionKey) {
        self::$key = base64_decode($encryptionKey);
    }

    // Encrypt data
    public static function encrypt($plainText) {
        if (empty(self::$key)) {
            throw new Exception("Encryption key not set");
        }

        // Generate a random IV
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        // Encrypt the data
        $encrypted = openssl_encrypt($plainText, self::$cipher, self::$key, 0, $iv);
        
        // Combine IV and encrypted data and encode as base64
        $combined = $iv . $encrypted;
        return base64_encode($combined);
    }

    // Decrypt data
    public static function decrypt($encryptedText) {
        if (empty(self::$key)) {
            throw new Exception("Encryption key not set");
        }

        // Decode the base64 data
        $combined = base64_decode($encryptedText);
        
        // Get the IV size
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        
        // Ensure we have enough data
        if (strlen($combined) <= $ivlen) {
            throw new Exception("Invalid encrypted data");
        }
        
        // Extract IV and ciphertext
        $iv = substr($combined, 0, $ivlen);
        $encrypted = substr($combined, $ivlen);
        
        // Decrypt and return
        return openssl_decrypt($encrypted, self::$cipher, self::$key, 0, $iv);
    }
}
?>