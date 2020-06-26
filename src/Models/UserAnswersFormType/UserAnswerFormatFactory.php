<?php
declare(strict_types=1);

namespace App\Models\UserAnswersFormType;

use  App\Models\UserAnswersFormType\UserAnswerBooleanFormType;
use  App\Models\UserAnswersFormType\UserAnswerMultiSelectFormType;

/**
 * A factory that return an  object that handle user responses based of type of html form that was used
 *
 */

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
