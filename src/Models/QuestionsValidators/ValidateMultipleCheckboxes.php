<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;

class ValidateMultipleCheckboxes extends AbstractValidators
{

    
    /**
     * TBD: it's a partial validation until we receive validation criteria for each question from Guide Match API
     * Validate user answer for a checkboxes list, at this moment it validates only for empty
     *
     * @return void
     */
    public function validate()
    {
        if (!empty($this->userAnswer['uuid'])) {
            $this->isValid = true;
        } else {
            $this->isValid = false;
            $this->errorMessage = ErrorMessages::EMPTY_USER_ANSWER;
        }
    }
}
