<?php
declare(strict_types=1);

namespace App\Models;

class Encrypt{

    private $encryptedString;

    function __construct(string $stringToEncrypt)
    {
        $this->encryptString($stringToEncrypt);
        
    }

    private function encryptString(string $stringToEncrypt){

        $this->encryptedString = openssl_encrypt(
            $stringToEncrypt, 
            getenv('CIPHER_METHOD_128'), 
            getenv('APP_SECRET'),
            0, 
            getEnv('ENCRYPTION_IV')
        ); 
    }

    public function getEncryptedString(){
        return $this->encryptedString;
    }
}
?>