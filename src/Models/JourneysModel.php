<?php


declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchGetJourneysApi;

class JourneysModel
{
  
    private $journeysApi;
    private $journeys = [];
    private $countJourneys = 0;

    /**
     *
     * @param GuideMatchGetJourneysApi $journeysApi
     * 
     */
    public function __construct(GuideMatchGetJourneysApi $journeysApi, string $searchBy)
    {
        $this->journeysApi = $journeysApi;
        $this->setJourneys($searchBy);
        $this->setNumberOfJourneys();
    }


    public function getJourneys()
    {
        return $this->journeys;
    }

    /**
     * Set journeys
     * @return void
     */
    private function setJourneys(string $searchBy)
    {
        $this->journeys = $this->journeysApi->searchJourneys($searchBy);
        
    }

    public function setNumberOfJourneys(){
        $this->countJourneys =  count($this->journeys);
    }

    public function getNumberOfJourneys(){
        return $this->countJourneys;
    }

}
