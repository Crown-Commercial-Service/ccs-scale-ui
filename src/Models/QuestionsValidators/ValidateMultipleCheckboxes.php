<?php 
declare(strict_types=1);

namespace App\Models\QuestionsValidators;
use App\Models\QuestionsValidators\AbstractValidators;

class ValidateMultipleCheckboxes extends AbstractValidators{

    
    /**
     * validate user answer for a checkboxes list
     *
     * @return void
     */
    public function validate(){
        if(!empty($this->userAnswer['uuid'])){

            $this->isValid = true;

        }else{

            $this->isValid = false;
            $this->errorMessage = ErrorMessages::EMPTY_USER_ANSWER;
        }
        
    }
}

?>