<?php

namespace App\Tests\App\Models;
use PHPUnit\Framework\TestCase;
use App\Models\Encrypt;
use App\Models\Decrypt;


class TestEncryptDecrypt extends TestCase{

    private $dataToBeDecripted;
    
    private function arrayToBeEncrypted(){

        $this->dataToBeDecripted = [
            0 => [
                "question" => [
                "id" => "ccb5a43a-75b5-11ea-bc55-0242ac130003",
                "text" => "Are you looking for a product, service or both?",
                "hint" => "Choose one option:",
                "type" => "list",
            ],
            "answers" =>[
                0 =>[
                    "answerText" => "Service",
                    "answer" => "b879fe0c-654e-11ea-bc55-0242ac130003",
                ]
            ],
            "variableName" => ""
            ]
        ];
    }
    
    public function testEcryptString(){

     
        $this->arrayToBeEncrypted();
        $string = json_encode($this->dataToBeDecripted);
        $encrypt = new Encrypt($string);
        
        $this->assertTrue(!empty($encrypt->getEncryptedString()));
        $this->assertTrue(is_string($encrypt->getEncryptedString()));

        return $encrypt->getEncryptedString();
    }

    /**
     * @depends testEcryptString
     *
     * 
     */
    public function testDecryptedString($stringToBeDecripted){

        $this->arrayToBeEncrypted();
        $decrypt = new Decrypt($stringToBeDecripted);
        $decriptedString = $decrypt->getDecryptedString();
        $this->assertTrue(!empty($decriptedString));
        $this->assertTrue(is_string($decriptedString));
        $this->assertTrue(is_array(json_decode($decriptedString)));
        $this->assertTrue(json_encode($this->dataToBeDecripted) === $decriptedString);

        

    }
}
