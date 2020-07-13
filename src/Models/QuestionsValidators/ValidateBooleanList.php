<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;
use App\Models\QuestionsValidators\ErrorMessages;

class ValidateBooleanList extends AbstractValidators
{

   
    /**
     * TBD: it's a partial validation until we receive validation criteria for each question from Guide Match API
     * Validate user answer from a boolean list,  at this moment it validates only for empty
     *
     * @return void
     */
    public function validate()
    {
        if (!empty($this->userAnswer['uuid'])) {
            $this->isValid = true;

            if (isset($this->userAnswer[$this->userAnswer['uuid']])) {
                if (empty($this->userAnswer[$this->userAnswer['uuid']])) {
                    $this->isValid = false;
                    $this->errorMessage = ErrorMessages::EMPTY_INPUT;
                }
            }

            if(!empty($this->userAnswer['inputType-'.$this->userAnswer['uuid']])){

                if($this->userAnswer['inputType-'.$this->userAnswer['uuid']] == 'number'){
                    

                    if(!is_numeric($this->userAnswer[$this->userAnswer['uuid']])){

                        $this->isValid = false;
                        $this->errorMessage = ErrorMessages::SHOULD_BE_NUMBER;

                    }else{
                        if($this->userAnswer[$this->userAnswer['uuid']] <= 0){
                            $this->isValid = false;
                            $this->errorMessage = ErrorMessages::SHOUD_BE_A_POZITIVE_NUMBER;
                        }
                    }
                }

            }

        } else {
            $this->isValid = false;
            $this->errorMessage = ErrorMessages::EMPTY_USER_ANSWER;
        }
    }
}
