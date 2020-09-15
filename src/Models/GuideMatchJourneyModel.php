<?php

declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;
use App\Models\GuideMatchResponseType;
use App\Models\UserAnswerApi;
use Exception;

use function foo\func;

class GuideMatchJourneyModel
{
    private $journeyApi;
    
    private $uuid;

    private $definedAnswers;

    private $type;

    private $text;

    private $hint = '';

    private $journeyInstanceId;

    private $journeyHistory;

    private $apiResponseType;

    private $agreementData;

    private $failureValidations = [];

    /**
     * Get Guide Match Api response
     *
     * @param GuideMatchJourneyApi $journeyApi
     */
    public function __construct(GuideMatchJourneyApi $journeyApi)
    {
        $this->journeyApi = $journeyApi;
    }


    /**
     * Set Api response variables
     *
     * @param array $apiResponse
     * @return void
     */
    private function handleApiResponse(array $apiResponse)
    {
        if (!empty($apiResponse[0]['question']['id'])) {
            $this->setUuid($apiResponse[0]['question']['id']);
        }

        if (!empty($apiResponse[0]['question']['type'])) {
            $this->setType($apiResponse[0]['question']['type']);
        }
        
        if (!empty($apiResponse[0]['question']['text'])) {
            $this->setText($apiResponse[0]['question']['text']);
        }

        if (!empty($apiResponse[0]['question']['hint'])) {
            $this->setHint($apiResponse[0]['question']['hint']);
        }

        if (!empty($apiResponse[0]['answerDefinitions'])) {
            $this->setDefinedAnswers($apiResponse[0]['answerDefinitions']);
            $this->orderAnswerDefinitions();
        }

        if (!empty($apiResponse[0]['failureValidations'])) {
            $this->setFailureValidation($apiResponse[0]['failureValidations']);
        }
    }

    /**
     * Call API for start Guide Match
     *
     * @param [type] $journeyUuid
     * @param [type] $searchBy
     * @return void
     */
    public function startJourney(string $journeyUuid, string $searchBy)
    {
        $apiResponse =  $this->journeyApi->startJourney($searchBy, $journeyUuid);

        if (!empty($apiResponse['journeyInstanceId'])) {
            $this->setJourneyInstanceId($apiResponse['journeyInstanceId']);
        }
        // dump($apiResponse);die();

        $this->handleApiResponse($apiResponse['questions']);
    }

    /**
     * setters for Journey Instance Id
     *
     * @param string $journeyInstanceId
     * @return void
     */
    private function setJourneyInstanceId(string $journeyInstanceId)
    {
        $this->journeyInstanceId = $journeyInstanceId;
    }

    /**
     * getter for Journey Instance Id
     *
     * @return string
     */
    public function getJourneyInstanceId()
    {
        return $this->journeyInstanceId;
    }
    
    /**
     * setter for question uuid
     *
     * @param string $uuid
     * @return string
     */
    private function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * getter for question uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Setter for type of questions list. I could be: boolean, list multiselect
     *
     * @param string $type
     * @return void
     */
    private function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Getter for type of questions list.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for question text
     *
     * @param string $text
     * @return void
     */
    private function setText(string $text)
    {
        $this->text = $text;
    }
 
    /**
     * Getter for question text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Setter for question hint
     *
     * @param string $hint
     * @return void
     */
    private function setHint(string $hint)
    {
        $this->hint = $hint;
    }
 
    /**
     * Getter for question hint
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Setter for predefined anwers of question
     *
     * @param array $definedAnswers
     * @return void
     */
    private function setDefinedAnswers(array $definedAnswers)
    {
        $this->definedAnswers = $definedAnswers;
    }
 
    /**
     * Getter for predefined answers of question
     *
     * @return array
     */
    public function getDefinedAnswers()
    {
        return $this->definedAnswers;
    }

    /**
     * Setter for journey history
     *
     * @param array $journeyHistory
     * @return void
     */
    private function setJourneyHistory(array $journeyHistory)
    {
        $this->journeyHistory = $journeyHistory;
    }
 
    /**
     * Getter for journey history
     *
     * @return array
     */
    public function getJourneyHistory()
    {
        return $this->journeyHistory;
    }


    
    /**
     * Get question from history which was on specific step of journey
     *
     * @param int $step
     * @return array
     */
    public function getHistoryQuestion($step)
    {
        return !empty($this->journeyHistory[$step]['question']) ? $this->journeyHistory[$step]['question'] : [];
    }

    public function getHistoryAnswers($step)
    {
        $questionAnswers = !empty($this->journeyHistory[$step]['answers']) ? $this->journeyHistory[$step]['answers'] : [];

        $answers = [];
        foreach ($questionAnswers as $answer) {
            $answers[] = $answer['answer'];
        }

        return $answers;
    }

    /**
     * Get answers of a question from full journey history
     *
     * @param string $questionId
     * @param array $historyAnswers
     * @return array
     */
    public function getQuestionAnswers(string $questionId, array $historyAnswers)
    {
        if (
            empty($questionUuid) &&
            empty($historyAnswers)
        ) {
            throw new Exception('Invalid parameters');
        }

        foreach ($historyAnswers as $questions) {
            if ($questions['question']['id'] === $questionId) {
             
                foreach ($questions['answers'] as $answers) {
               
                    $userAnswers[$answers['answerText']]['selected'] = true;
                    $userAnswers[$answers['answerText']]['answer'] = $answers['answer'];
                }
            }
        }
        return $userAnswers;
    }

    /**
     * Return API response page type
     *
     * @return void
     */
    public function getApiResponseType()
    {
        return  $this->apiResponseType;
    }

    /**
     * Set API response page type
     *
     * @param [type] $responseType
     * @return void
     */
    public function setApiResponseType($responseType)
    {
        $this->apiResponseType = $responseType;
    }

    /**
     * Undocumented function
     *
     * @param array $agreementData
     * @return void
     */
    private function setAgreementData(array $agreementData)
    {
        $this->agreementData = $agreementData;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getAgreementData()
    {
        return $this->agreementData;
    }


    /**
     * Get question details from API
     *
     * @param string $journeyUuid
     * @param string $questionUuid
     * @return void
     */
    public function setQuestionDetails(string $journeyUuid, string $questionUuid)
    {
        $apiResponse =  $this->journeyApi->getJourneyQuestion($journeyUuid, $questionUuid);
        $this->handleApiResponse($apiResponse);
    }


    /**
     * Get next questions from Guide Match Api according to user response
     *
     * @param string $journeyUuid
     * @param string $questionUuid
     * @param array $questionResponse
     * @return void
     * @throws Exception
     */
    public function getDecisionTree(string $journeyUuid, string $questionUuid, array $questionResponse)
    {
        $apiResponse = $this->journeyApi->getDecisionTree($journeyUuid, $questionUuid, $questionResponse);
        if (empty($apiResponse)) {
            throw new Exception('Error API response');
        }
  //      dd($apiResponse);
        $this->setApiResponseType($apiResponse['outcome']['outcomeType']);
        $this->setJourneyHistory($apiResponse['journeyHistory']);

        if ($this->apiResponseType != GuideMatchResponseType::GuideMatchResponseSupport) {
            if (!empty($apiResponse['outcome']['data'])) {
                if ($this->apiResponseType == GuideMatchResponseType::GuideMatchResponseAgreement) {
                    $this->setAgreementData($apiResponse['outcome']['data']);
                }
                
                $this->handleApiResponse($apiResponse['outcome']['data']);
            }
        }
    }

    /**
     * Order predefined answers
     *
     * @return array
     */
    private function orderAnswerDefinitions()
    {
        usort($this->definedAnswers, function ($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });
    }

    private function setFailureValidation($failureValidations){
       
        $this->failureValidations = $failureValidations;
    //    dd($this->failureValidations);
    }

    public function getFailureValidation(){
       return $this->failureValidations;
    }
}
