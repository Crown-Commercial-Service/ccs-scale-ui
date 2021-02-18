<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;
use App\Models\QuestionsValidators\ErrorTypeCodes;


class ValidateMultipleCheckboxes extends AbstractValidators
{

    public function validate()
    {
        $this->isValid = true;
        if (empty($this->userAnswer['uuid'])) {
            $this->isValid = false;
            $this->errorCode = ErrorTypeCodes::NO_SELECTION;
        }
    }
}
