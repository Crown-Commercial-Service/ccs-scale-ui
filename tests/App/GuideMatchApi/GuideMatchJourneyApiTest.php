<?php

namespace App\Tests\App\GuideMatchyApi;
use PHPUnit\Framework\TestCase;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpClient\HttpClient;


class GuideMatchJourneyApiTest extends TestCase{

    
    public function testStartJourney(){

        $searchBy = 'linen';
        $journeyUuid = 'b87a0636-654e-11ea-bc55-0242ac130003';
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $response = $api->startJourney($searchBy, $journeyUuid);
        $this->assertTrue(!empty($response));
        $this->assertTrue(!empty($response['journeyInstanceId'])); 
        $this->assertTrue(!empty($response['questions']));        
        $this->assertArrayHasKey('journeyInstanceId', ($response));
        $this->assertArrayHasKey('questions', ($response));
    }


    /**
     * Should be run after a journey was completed
     *
     * @return void
     */
    //  public function testJourneyHistory(){

    //     $httpClient = HttpClient::create();
    //     $journeyUuid = 'b87a0636-654e-11ea-bc55-0242ac130003';
    //     $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
    //     $response = $api->getJourneyHistory($journeyUuid);
    //     dd($response);

    // }


    
}