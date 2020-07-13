<?php
declare(strict_types=1);

namespace App\Models;

class UserAnswers
{
    
    /**
     * Format user anwers to be display on the journey result page
     *
     * @return array
     */
    public function formatForView(array $userAnswers)
    {
        $answersFormart = [];
        foreach ($userAnswers as $answers) {
            $nrAnswers = count($answers['answers']);
            $counter = 1;
            $answerTxt = '';
           
            foreach ($answers['answers'] as $answer) {
                $answerTxt .= $counter < $nrAnswers ?
                             $answer['answerText'] . (empty($this->checkIfTheAnswerIsId($answer['answer'])) ? "({$answer['answer']})" : ''). ', ' :
                             $answer['answerText'].(empty($this->checkIfTheAnswerIsId($answer['answer'])) ? "({$answer['answer']})":'');
                $counter++;
            }

            $answersFormart[] = [
                'question' => $answers['question']['text'],
                'questionId' => $answers['question']['id'],
                'prevQuestionId' =>  $answers['question']['id'],
                'answerTxt' => $answerTxt,
                'changeUrl' => '#'
            ];
        }
        return $answersFormart;
    }

    /**
     * Check if an answer is an UUID
     *
     * @param string $answer
     * @return boolean
     */
    private function checkIfTheAnswerIsId(string $answer)
    {
        return preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $answer);
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

    /**
     * Format user answers to be displayed if answers are invalid :  for conditional input field
     *
     * @param array $postData
     * @param array $preDefinedAnwers
     * @return array
     */
    public function getFormatUserAnswers(array $postData, array $preDefinedAnwers)
    {
        $answers = [];

        foreach ($preDefinedAnwers as $answer) {
            if (!empty($answer['conditionalInput'])) {
                if (in_array($answer['id'], $postData)) {
                    $answers = [
                        'answerText' => $answer['text'],
                        'answer'=> $postData[$answer['id']],
                        $answer['text'] => true

                ];
                }
            }
        }
        return $answers;
    }
}
