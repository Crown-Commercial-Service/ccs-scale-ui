<?php
declare(strict_types=1);

namespace App\Models;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use App\GuideMatchApi\GuideMatchJourneyApi;


class GuideMatchModel{

    private $journeyData;
    private $httpClient;
    private $baseApiUrl;

    function __construct(CurlHttpClient $httpClient, string $baseApiUrl, string $searchBy)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
        $this->setJourneyId($searchBy);

    }
    
    private function setJourneyId(string $searchBy){

        $journeyApi = new GuideMatchJourneyApi($this->httpClient, $this->baseApiUrl);
        $this->journeyData = $journeyApi->getJourneyUuid( $searchBy);

    }

    public function getJurneyData(){

        return $this->journeyData;

    }
}

?>