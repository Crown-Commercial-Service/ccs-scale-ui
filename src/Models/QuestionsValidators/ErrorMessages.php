<?php
declare(strict_types=1);
namespace App\Models\QuestionsValidators;

class ErrorMessages
{
    const EMPTY_USER_ANSWER = 'You need to select something.';
    const EMPTY_INPUT ='Please add a value.';
    const SHOULD_BE_NUMBER = 'The value should be a number.';
    const SHOUD_BE_A_POZITIVE_NUMBER = 'Enter a value greater than zero.';
    const SHOULD_BY_STRING = 'The value should be a string.';
}
