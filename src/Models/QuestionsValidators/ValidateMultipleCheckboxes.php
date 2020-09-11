<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;

class ValidateMultipleCheckboxes extends AbstractValidators
{

    public function validate()
    {
        $this->isValid = true;
        if (empty($this->userAnswer['uuid'])) {
            $this->isValid = false;
        }
    }
}
