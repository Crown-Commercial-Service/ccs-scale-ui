<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;
use App\Models\QuestionsValidators\ErrorTypeCodes;

class ValidateBooleanList extends AbstractValidators
{
    

    public function validate()
    {
        if (!empty($this->userAnswer['uuid'])) {
            $this->isValid = true;
            if (isset($this->userAnswer[$this->userAnswer['uuid']])) {
                if (empty($this->userAnswer[$this->userAnswer['uuid']])) {
                    $this->isValid = false;
                    $this->errorCode = ErrorTypeCodes::NO_SELECTION;
                }
            }

            if (!empty($this->userAnswer['inputType-'.$this->userAnswer['uuid']])) {
                if ($this->userAnswer['inputType-'.$this->userAnswer['uuid']] == 'number') {
                    if(empty($this->userAnswer[$this->userAnswer['uuid']])){
                        $this->isValid = false;
                        $this->errorCode = ErrorTypeCodes::NO_VALUE;
                    }
                    else if (!is_numeric($this->userAnswer[$this->userAnswer['uuid']])) {
                        $this->isValid = false;
                        $this->errorCode = ErrorTypeCodes::CHECK_NUMBER;
                    } else {
                        if ($this->userAnswer[$this->userAnswer['uuid']] <= 0) {
                            $this->isValid = false;
                            $this->errorCode = ErrorTypeCodes::CHECK_POSITIVE_NUMBER;
                        }

                        if(filter_input(INPUT_POST,$this->userAnswer[$this->userAnswer['uuid']], FILTER_VALIDATE_INT)){
                            dd($this->userAnswer[$this->userAnswer['uuid']]);
                            die('x');
                            $this->isValid = false;
                            $this->errorCode = ErrorTypeCodes::CHECK_WHOLE_NUMBER;
                        }

                    }
                }
            }
        } else {
            $this->isValid = false;
            $this->errorCode = ErrorTypeCodes::NO_SELECTION;
        }
    }
}
