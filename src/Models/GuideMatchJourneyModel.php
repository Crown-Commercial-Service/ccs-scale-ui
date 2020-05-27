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

    /**
     * Get Guide Match Api response
     *
     * @param GuideMatchJourneyApi $journeyApi
     * @param string $journeyUuid
     * @param string $questionUuid
     * @param array $questionResponse
     */
    public function __construct(GuideMatchJourneyApi $journeyApi, string $journeyUuid, string $questionUuid, array $questionResponse=[])
    {
        $this->journeyApi = $journeyApi;

        if (empty($questionResponse)) {
            $this->getApiResponse($journeyUuid, $questionUuid);
        } else {
            $this->getDecisionTree($journeyUuid, $questionUuid, $questionResponse);
        }
    }

    /**
     * Get first set of questions from Guide Match Api
     *
     * @param string $journeyUuid
     * @param string $questionUuid
     * @return void
     */
    private function getApiResponse(string $journeyUuid, string $questionUuid)
    {
        $apiResponse = $this->journeyApi->getQuestions($journeyUuid, $questionUuid);

        if (empty($apiResponse)) {
            throw new Exception('Error api response');
        }

        $this->handleApiResponse($apiResponse);
    }

    /**
     * Set Api response variables
     *
     * @param array $apiResponse
     * @return void
     */
    private function handleApiResponse(array $apiResponse)
    {
        if (!empty($apiResponse['uuid'])) {
            $this->setUuid($apiResponse['uuid']);
        }

        if (!empty($apiResponse['type'])) {
            $this->setType($apiResponse['type']);
        }
        
        if (!empty($apiResponse['text'])) {
            $this->setText($apiResponse['text']);
        }

        if (!empty($apiResponse['definedAnswers'])) {
            $this->setDefinedAnswers($apiResponse['definedAnswers']);
        }

        if (!empty($apiResponse['hint'])) {
            $this->setHint($apiResponse['hint']);
        }
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
    private function getDecisionTree(string $journeyUuid, string $questionUuid, array $questionResponse)
    {
        $apiResponse = $this->journeyApi->getDecisionTree($journeyUuid, $questionUuid, $questionResponse);
        if (empty($apiResponse)) {
            throw new Exception('Error api response');
        }

        if ($apiResponse['outcomeType']=='lot') {
            dump($apiResponse);
            die('Final Journey');
        }
        $this->handleApiResponse($apiResponse['data']);
    }
}
