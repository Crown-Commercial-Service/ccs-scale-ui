<?php
declare(strict_types=1);

namespace App\Models;

class Decrypt
{
    private $decryptedString;

    public function __construct(string $stringToDecrypt)
    {
        $stringWithSlashes = str_replace('*', '/', $stringToDecrypt);
        $this->decryptString($stringWithSlashes);
    }

    /**
     * Decrypt a string, crypted with 128 bytes length
     *
     * @param string $stringToDecrypt
     * @return void
     */
    private function decryptString(string $stringToDecrypt)
    {
        $this->decryptedString = openssl_decrypt(
            $stringToDecrypt,
            getenv('CIPHER_METHOD_128'),
            getenv('APP_SECRET'),
            0,
            getEnv('ENCRYPTION_IV')
        );
    }

    /**
     * Getter method for decrypted string
     *
     * @return string
     */
    public function getDecryptedString()
    {
        return $this->decryptedString;
    }
}
