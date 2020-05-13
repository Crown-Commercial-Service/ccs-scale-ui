<?php

namespace App\Tests\App\GuideMatchyApi;

use PHPUnit\Framework\TestCase;
use App\GuideMatchApi\GuideMatchJourneyApi;

class GuideMatchJourneyApiTest extends TestCase
{
    public function testGetJourneyUuid()
    {
        $search_by = 'linen';
        $api = new GuideMatchJourneyApi();
        $base_api_url =  getenv('GUIDE_MATCH_DECISION_TREE_API');
        $response = $api->getJourneyUuid($base_api_url, $search_by);
        $this->assertTrue(!empty($response));
        $this->assertTrue(!empty($response[0]));
        $this->assertArrayHasKey('uuid', ($response[0]));
        $this->assertArrayHasKey('questionUuid', ($response[0]));
    }


    public function testGetQuestion()
    {
        $api = new GuideMatchJourneyApi();
        $base_api_url =  getenv('GUIDE_MATCH_DECISION_TREE_API');
        $response = $api->getJourneyUuid($base_api_url, 'linen');

        $this->assertArrayHasKey('uuid', ($response[0]));
        $this->assertArrayHasKey('questionUuid', ($response[0]));

        $questions= $api->getQuestions($base_api_url, $response[0]['uuid'], $response[0]['questionUuid']);
        $this->assertTrue(!empty($questions));
        $this->assertTrue(!empty($questions));
        $this->assertArrayHasKey('uuid', ($questions));
        $this->assertArrayHasKey('definedAnswers', ($questions));
    }


    public function testGetDecisionTree()
    {
        $search_by = 'linen';

        $api = new GuideMatchJourneyApi();
        $base_api_url =  getenv('GUIDE_MATCH_DECISION_TREE_API');
        
        //start journey
        $start_journery_api_response = $api->getJourneyUuid($base_api_url, $search_by);
        $this->assertArrayHasKey('uuid', ($start_journery_api_response[0]));
        $this->assertArrayHasKey('questionUuid', ($start_journery_api_response[0]));
        $uuid = $start_journery_api_response[0]['uuid'];
        $questionUuid = $start_journery_api_response[0]['questionUuid'];

        //get first set of questions
        $get_questions_api_response = $api->getQuestions($base_api_url, $uuid, $questionUuid);
        $this->assertTrue(!empty($get_questions_api_response));
        $this->assertArrayHasKey('uuid', ($get_questions_api_response));
        $this->assertArrayHasKey('definedAnswers', ($get_questions_api_response));

        $definedAnswers = $get_questions_api_response['definedAnswers'];

        //set a random user response
        $response_question_number = rand(0, count($definedAnswers)-1);
        $user_response = $definedAnswers[$response_question_number]['uuid'];

        //get api response
        $decision_tree_api_response =  $api->getDecisionTree($base_api_url, $uuid, $questionUuid, [$user_response]);
        $this->assertTrue(!empty($decision_tree_api_response));
        $this->assertArrayHasKey('outcomeType', $decision_tree_api_response);

        if ($decision_tree_api_response['outcomeType'] == 'lot') {
            $this->assertArrayHasKey('agreementId', $decision_tree_api_response['data'][0]);
            $this->assertArrayHasKey('agreementName', $decision_tree_api_response['data'][0]);
        } else {
            $this->assertArrayHasKey('definedAnswers', ($decision_tree_api_response['data']));
        }
    }
}
