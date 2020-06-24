<?php
declare(strict_types=1);

namespace App\Models\QuestionsValidators;
use App\Models\QuestionsValidators\ValidateBooleanList;
use App\Models\QuestionsValidators\ValidateMultipleCheckboxes;

/**
 * A factory to set what validator is used depending by question form type
 */
 class ValidatorsFactory{

    public static function getValidator(string $formType, array $userAnswer){

        switch ($formType){

            case 'boolean':
                return new ValidateBooleanList($userAnswer);
            break;

            case 'multiselect':
                return new ValidateMultipleCheckboxes($userAnswer);
            break;

        }
    }
 }
?>