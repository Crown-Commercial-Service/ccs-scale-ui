<?php
declare(strict_types=1);

namespace App\Models;

class Decrypt
{
    private $decryptedString;

    public function __construct(string $stringToDecrypt)
    {
        $this->decryptString($stringToDecrypt);
    }

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

    public function getDecryptedString()
    {
        return $this->decryptedString;
    }
}
