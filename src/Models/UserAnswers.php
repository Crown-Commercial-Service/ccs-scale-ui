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

    public function getAnswersFromHistory($history, $step)
    {
        $answersData = $history[$step-1];
        $answersToPreviousQuestion = [];
        foreach ($answersData['answers'] as $answer) {
            $answersToPreviousQuestion[] = $answer['answer'];
        }
        
        return $answersToPreviousQuestion;
    } 
}
