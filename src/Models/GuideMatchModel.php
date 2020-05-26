<?php
declare(strict_types=1);

namespace App\Models;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use App\GuideMatchApi\GuideMatchJourneyApi;
// use App\Models\Entities\JourneyEntity;


class GuideMatchModel{

  
    private $httpClient;
    private $baseApiUrl;
    private $journeyUuid;
    private $journeyName;
    private $questionUuid;
    private $journeyData;

    function __construct(CurlHttpClient $httpClient, string $baseApiUrl, string $searchBy)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
        $this->setJourneyId($searchBy);

    }
    
    private function setJourneyId(string $searchBy){

        $journeyApi = new GuideMatchJourneyApi($this->httpClient, $this->baseApiUrl);
        $this->journeyData = $journeyApi->getJourneyUuid($searchBy);
        if (!empty($this->journeyData)) {
            $this->setJourneyUuid($this->journeyData[0]['uuid']);
            $this->setJourneyQuestionUuid($this->journeyData[0]['uuid']);
            $this->setJourneyName($this->journeyData[0]['name']);
        }
    }

    private function setJourneyUuid($journeyUuid){
        $this->journeyUuid = $journeyUuid;
    }

    public function getJourneyUuid(){
        return $this->journeyUuid;
    }

    private function setJourneyQuestionUuid($questionUuid){
        $this->questionUuid = $questionUuid;
    }

    public function getJourneyQuestionUuid(){
        return $this->questionUuid;
    }

    private function setJourneyName($name){
        $this->journeyName = $name;
    }

    public function getJourneyName(){
        return $this->journeyName ;
    }

    public function getJurneyData(){
        return $this->journeyData;
    }
}

?>