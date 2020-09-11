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

    

    abstract public function validate();
}
