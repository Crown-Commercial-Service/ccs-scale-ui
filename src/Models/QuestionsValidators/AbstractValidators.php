<?php 
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

abstract class AbstractValidators{

    protected $userAnswer;
    protected $isValid;
    protected $errorMessage;
    
    public function __construct(array $userAnswer)
    {
        $this->userAnswer = $userAnswer;
        $this->validate();
    }

    public function isValid(){
        return $this->isValid;
    }

    public function getErrorMessage(){
        return $this->errorMessage;
;
    }

    abstract public function validate();
}
?>