<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;

use App\Models\QuestionsValidators\AbstractValidators;

class ValidateBooleanList extends AbstractValidators
{

    public function validate()
    {
        if (!empty($this->userAnswer['uuid'])) {
            $this->isValid = true;

            if (isset($this->userAnswer[$this->userAnswer['uuid']])) {
                if (empty($this->userAnswer[$this->userAnswer['uuid']])) {
                    $this->isValid = false;
                }
            }

            if (!empty($this->userAnswer['inputType-'.$this->userAnswer['uuid']])) {
                if ($this->userAnswer['inputType-'.$this->userAnswer['uuid']] == 'number') {
                    if (!is_numeric($this->userAnswer[$this->userAnswer['uuid']])) {
                        $this->isValid = false;
                    } else {
                        if ($this->userAnswer[$this->userAnswer['uuid']] <= 0) {
                            $this->isValid = false;
                        }
                    }
                }
            }
        } else {
            $this->isValid = false;
        }
    }
}
