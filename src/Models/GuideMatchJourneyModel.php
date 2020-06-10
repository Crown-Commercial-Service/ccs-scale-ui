<?php

declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;
use App\Models\GuideMatchResponseType;
use \Exception;

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

    private $lastJourneyQuestionAnswers = [];

    private $lastJourneyAction = [];

    private $apiResponseType;

    private $agreementData;

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

        if (!empty($apiResponse[0]['definedAnswers'])) {
            $this->setDefinedAnswers($apiResponse[0]['definedAnswers']);
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

        $this->handleApiResponse($apiResponse['questions']);
    }

    /**
     * setters for Journey Instance Id
     *
     * @param [type] $journeyInstanceId
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


   
    private function setLastJourneyAnswers()
    {
        if (!empty($this->lastJourneyAction['answers'])) {
            foreach ($this->lastJourneyAction['answers'] as $answer) {
                $this->lastJourneyQuestionAnswers[] = $answer['answer'];
            }
        }
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

        $answers = [];

        foreach ($historyAnswers as $questions) {
            if ($questions['question']['id'] === $questionId) {
                foreach ($questions['answers'] as $answers) {
                    $answers[$answers['answer']] = true;
                }
            }
        }
       
        return $answers;
    }

    public function getApiResponseType(){
        return  $this->apiResponseType;
    }

    public function setApiResponseType($responseType){

        if ($responseType != 'question') {
            $this->apiResponseType = GuideMatchResponseType::GuideMatchResponseAgreement;
        }else{
            $this->apiResponseType = GuideMatchResponseType::GuideMatchResponseQuestion;
        }
        
    }

    private function setAgreementData(array $agreementData){
        $this->agreementData = $agreementData;
    }

    public function getAgreementData(){
       return $this->agreementData;
    }

    /**
     * Get next questions from Guide Match Api according to user response
     *
     * @param string $journeyUuid
     * @param string $questionUuid
     * @param array $questionResponse
     * @return void
     */
    public function getDecisionTree(string $journeyUuid, string $questionUuid, array $questionResponse)
    {
        $apiResponse = $this->journeyApi->getDecisionTree($journeyUuid, $questionUuid, $questionResponse);

        if (empty($apiResponse)) {
            throw new Exception('Error API response');
        }

        $this->setApiResponseType($apiResponse['outcome']['outcomeType']);
        $this->setJourneyHistory($apiResponse['journeyHistory']);
       
        if($this->apiResponseType != GuideMatchResponseType::GuideMatchResponseQuestion){
          $this->setAgreementData($apiResponse['outcome']['data']);
        }
    
        $this->setLastJourneyAnswers();
        $this->handleApiResponse($apiResponse['outcome']['data']);
    }
}
