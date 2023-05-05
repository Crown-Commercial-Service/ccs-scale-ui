<?php
declare(strict_types=1);

namespace App\Models;

class Encrypt
{
    private $encryptedString;

    public function __construct(string $stringToEncrypt)
    {
        $this->encryptString($stringToEncrypt);
    }

    /**
     * Encrypt a string on 128 bytes length
     *
     * @param string $stringToEncrypt
     * @return void
     */
    private function encryptString(string $stringToEncrypt)
    {
        $this->encryptedString = openssl_encrypt(
            $stringToEncrypt,
            getenv('CIPHER_METHOD_128'),
            getenv('APP_SECRET'),
            0,
            getEnv('ENCRYPTION_IV')
        );
    }

    /**
     * Getter method for encrypted string
     *
     * @return string
     */
    public function getEncryptedString()
    {
        // remove slashes as symfony routing does like them
        $encryptedString = str_replace('/', '*', $this->encryptedString); 
        return $encryptedString;
    }
}
