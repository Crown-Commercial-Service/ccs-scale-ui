<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

abstract class AbstractValidators
{
    protected $userAnswer;
    protected $isValid;
    protected $errorMessage;
    
    public function __construct(array $userAnswer)
    {
        $this->userAnswer = $userAnswer;
        $this->validate();
    }

    /**
     * Return validation result
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * If a validation is failed return a error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
        ;
    }

    abstract public function validate();
}
