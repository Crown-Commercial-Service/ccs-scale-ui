<?php

namespace App\Tests\App\Models;
use PHPUnit\Framework\TestCase;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use App\Tests\App\Models\ApiResponses\ApiQuestionResponses;



class GuideMatchJourneyModelTest extends TestCase{

    
    public function testStartJourney(){

        $searchBy = 'linen';
        $journeyUuid = 'b87a0636-654e-11ea-bc55-0242ac130003';
        $apiResponses = new ApiQuestionResponses();
        $response =  new MockResponse($apiResponses->startJourneyApiResponse());
        $client = new MockHttpClient($response);
        $api = new GuideMatchJourneyApi($client, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $model = new GuideMatchJourneyModel($api); 

        $model->startJourney($journeyUuid, $searchBy);
        
        $this->assertTrue(!empty($model->getHint()));
        $this->assertTrue(!empty($model->getDefinedAnswers()));
        $this->assertTrue(is_array($model->getDefinedAnswers()));
        $this->assertTrue(empty($model->getJourneyHistory()));
        $this->assertTrue(!empty($model->getUuid()));
        $this->assertTrue(is_string($model->getUuid()));
        $this->assertTrue(empty($model->getHistoryQuestion(0)));
        $this->assertTrue(empty($model->getApiResponseType()));
        $this->assertTrue(empty($model->getAgreementData()));
        

    } 
    
    
    public function testQuestionResponse(){

        $journeyInstanceId = '9d54f22a-4927-4d63-8b0d-d190ea644ee8';
        $questionUuid = 'ccb5a43a-75b5-11ea-bc55-0242ac130003';

        $userQuestionResponse =  [
            0 => [
                "id" => "b879fe0c-654e-11ea-bc55-0242ac130003",
                "value" => null
            ]
        ];

        $apiResponses = new ApiQuestionResponses();
        $response =  new MockResponse($apiResponses->questionResponseMock());
        $client = new MockHttpClient($response);
        $api = new GuideMatchJourneyApi($client, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $model = new GuideMatchJourneyModel($api); 

        $model->getDecisionTree($journeyInstanceId, $questionUuid, $userQuestionResponse);
        $this->assertTrue(!empty($model->getHint()));
        $this->assertTrue($model->getHint() == 'This helps us find your best buying options. An estimate is fine.');
        $this->assertTrue(!empty($model->getDefinedAnswers()));
        $this->assertTrue(is_array($model->getDefinedAnswers()));
        $this->assertTrue(!empty($model->getJourneyHistory()));
        $this->assertTrue(!empty($model->getUuid()));
        $this->assertTrue(is_string($model->getUuid()));
        $this->assertTrue(!empty($model->getHistoryQuestion(0)));
        $this->assertTrue(is_array($model->getHistoryQuestion(0)));
        $this->assertTrue(!empty($model->getApiResponseType()));
        $this->assertTrue(($model->getApiResponseType() === 'question'));
        $this->assertTrue(empty($model->getAgreementData()));
        
    

    }
}
