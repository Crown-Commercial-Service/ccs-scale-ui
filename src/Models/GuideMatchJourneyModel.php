<?php

declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;
use \Exception;

class GuideMatchJourneyModel
{
    private $journeyApi;
    private $uuid;

    private $definedAnswers;

    // question type
    private $type;

    // question text
    private $text;

    // question hint
    private $hint = '';

    private $journeyInstanceId;

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
 
    public function startJourney($journeyUuid, $searchBy){

        $apiResponse =  $this->journeyApi->startJourney($searchBy,$journeyUuid);

        if (!empty($apiResponse['journeyInstanceId'])) {
            $this->setJourneyInstanceId($apiResponse['journeyInstanceId']);
        }

        $this->handleApiResponse($apiResponse['questions']);

    }

    private function setJourneyInstanceId($journeyInstanceId){

        $this->journeyInstanceId = $journeyInstanceId;
    }

    public function getJourneyInstanceId(){

        return $this->journeyInstanceId;
    }
    
    private function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    private function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    private function setText(string $text)
    {
        $this->text = $text;
    }
 
    public function getText()
    {
        return $this->text;
    }


    private function setHint(string $hint)
    {
        $this->hint = $hint;
    }
 
    public function getHint()
    {
        return $this->hint;
    }

    private function setDefinedAnswers(array $definedAnswers)
    {
        $this->definedAnswers = $definedAnswers;
    }
 
    public function getDefinedAnswers()
    {
        return $this->definedAnswers;
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
            throw new Exception('Error api response');
        }
     
        if($apiResponse['outcome']['outcomeType'] != 'question'){
            dump($apiResponse);
            die('Final Journey');
        }

        $this->handleApiResponse($apiResponse['outcome']['data']);
    }
}
