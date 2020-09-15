<?php

declare(strict_types=1);
namespace App\Models\QuestionsValidators;

class ErrorMessage {

    private $errorMessage = '';

    function __construct( string $errorTypeCode, array $apiErrorMessages)
    {
        $this->setErrorMessage($errorTypeCode, $apiErrorMessages);
        
    }

    /**
     * Set the error message that we received from API
     *
     * @param string $errorCode
     * @param array $apiErrorMessages
     * @return void
     */
    private function setErrorMessage(string $errorTypeCode, array $apiErrorMessages)
    {
       if(!empty($apiErrorMessages)){
           foreach($apiErrorMessages as $msg){

                if($msg['failureValidationTypeCode'] === $errorTypeCode){
                    $this->errorMessage = $msg['errorMessage'];
                    break;
                }
           }
       }
    }

    public function getErrorMessage(){

        return $this->errorMessage;

    }
}
?>