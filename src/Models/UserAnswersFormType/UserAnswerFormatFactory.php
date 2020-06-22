<?php
declare(strict_types=1);

namespace App\Models\UserAnswersFormType;

use  App\Models\UserAnswersFormType\UserAnswerBooleanFormType;
use  App\Models\UserAnswersFormType\UserAnswerMultiSelectFormType;

class UserAnswerFormatFactory
{
    public static function getFormTypeObject($type, $userAnswer)
    {
        switch ($type) {

            case 'boolean':
                return  new UserAnswerBooleanFormType($userAnswer);
            break;

            case 'multiselect':
               return  new UserAnswerMultiSelectFormType($userAnswer);
            break;
        }
    }
}
