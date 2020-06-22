<?php
declare(strict_types=1);

namespace App\Models;

class UserAnswers
{
    private $userAnswers;

    public function __construct(array $userAnswers)
    {
        $this->userAnswers = $userAnswers;
    }

    /**
     * Format user anwers to be display on the journey result page
     *
     * @return array
     */
    public function formatForView()
    {
        $answersFormart = [];
        $prevQuestionId = '';
        foreach ($this->userAnswers as $answers) {
            $nrAnswers = count($answers['answers']);
            $counter = 1;
            $answerTxt = '';
           
            foreach ($answers['answers'] as $answer) {
                $answerTxt .= $counter < $nrAnswers ? $answer['answerText'] . ', ' : $answer['answerText'];
                $counter++;
            }

            $answersFormart[] = [
                'question' => $answers['question']['text'],
                'questionId' => $answers['question']['id'],
                'prevQuestionId' => !empty($prevQuestionId) ? $prevQuestionId : $answers['question']['id'],
                'answerTxt' => $answerTxt,
                'changeUrl' => '#'
            ];
            $prevQuestionId = $answers['question']['id'];
        }
        return $answersFormart;
    }

    /**
     * Get the answers of user from a specific step of the journey history
     *
     * @param array $history
     * @param int $step
     * @return void
     */
    public function getAnswersFromHistory(array $history, int $step)
    {
        $answersData = $history[$step-1];
        $answersToPreviousQuestion = [];
        foreach ($answersData['answers'] as $answer) {
            $answersToPreviousQuestion[] = $answer['answer'];
        }
        
        return $answersToPreviousQuestion;
    }
}
