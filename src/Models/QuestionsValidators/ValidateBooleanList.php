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
        } else {
            $this->isValid = false;
            $this->errorMessage = ErrorMessages::EMPTY_USER_ANSWER;
        }
    }
}
