<?php
declare(strict_types=1);

namespace App\Models\UserAnswersFormType;

use App\Models\UserAnswersFormType\UserAnswerFormTypeInteface;

class UserAnswerBooleanFormType implements UserAnswerFormTypeInteface
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
            $id = '';
            if ($key === 'uuid') {
                $id = $value;
            }

            if (empty($id)) {
                continue;
            }

            $answer = !empty($userAnswer[$id]) ? $userAnswer[$id] : null;

            $this->formatedAnswers[] = [
                'id' => $id,
                'value' => $answer
            ];
        }
    }

    public function getAnswersFormated()
    {
        return $this->formatedAnswers;
    }
}
