<?php
declare(strict_types=1);

namespace App\Models\UserAnswersFormType;

use App\Models\UserAnswersFormType\UserAnswerFormTypeInteface;

class UserAnswerMultiSelectFormType implements UserAnswerFormTypeInteface
{
    private $formatedAnswers = [];

    public function __construct(array $userAnswer)
    {
        $this->setFormatAnswers($userAnswer);
    }

    /**
     * Format user answer to be send to Guide Match API
     *
     * @param array $userAnswer
     * @return void
     */
    private function setFormatAnswers($userAnswer)
    {
        foreach ($userAnswer as $key => $value) {
            if ($key === 'uuid') {
                if (is_array($value)) {
                    foreach ($value as $questionId) {
                        $answer = !empty($userAnswer[$questionId]) ? $userAnswer[$questionId] : null;
                        $this->formatedAnswers[] = [
                            'id' => $questionId,
                            'value' => $answer
                        ];
                    }
                }
            }
        }
    }

    public function getAnswersFormated()
    {
        return $this->formatedAnswers;
    }
}
