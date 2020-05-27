<?php

declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;
use \Exception;

class GuideMatchModel
{
    private $journeyApi;
    private $journeyUuid;
    private $journeyName;
    private $questionUuid;
    private $journeyData;
    

    /**
     * Get Api Response to start a  journey
     *
     * @param GuideMatchJourneyApi $api
     * @param string $searchBy
     */
    public function __construct(GuideMatchJourneyApi $api, string $searchBy)
    {
        $this->journeyApi = $api;
        $this->setJourneyId($searchBy);
    }
    
    private function setJourneyId(string $searchBy)
    {
        $this->journeyData = $this->journeyApi->getJourneyUuid($searchBy);

        if (!empty($this->journeyData)) {
            if (!empty($this->journeyData[0]['uuid'])) {
                $this->setJourneyUuid($this->journeyData[0]['uuid']);
            }

            if (!empty($this->journeyData[0]['questionUuid'])) {
                $this->setJourneyQuestionUuid($this->journeyData[0]['questionUuid']);
            }

            if (!empty($this->journeyData[0]['questionUuid'])) {
                $this->setJourneyName($this->journeyData[0]['name']);
            }
        } else {
            throw new Exception("Don't exists a journey for : {$searchBy}");
        }
    }

    private function setJourneyUuid($journeyUuid)
    {
        $this->journeyUuid = $journeyUuid;
    }

    public function getJourneyUuid()
    {
        return $this->journeyUuid;
    }

    private function setJourneyQuestionUuid($questionUuid)
    {
        $this->questionUuid = $questionUuid;
    }

    public function getJourneyQuestionUuid()
    {
        return $this->questionUuid;
    }

    private function setJourneyName($name)
    {
        $this->journeyName = $name;
    }

    public function getJourneyName()
    {
        return $this->journeyName ;
    }

    public function getJurneyData()
    {
        return $this->journeyData;
    }
}
