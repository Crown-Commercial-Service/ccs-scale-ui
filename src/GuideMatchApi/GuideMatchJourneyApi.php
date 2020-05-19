<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Component\HttpClient\HttpClient;
use \Exception;

class GuideMatchJourneyApi
{


    /**
     * Start Guide Match Journey
     *
     * @param string $base_api_url
     * @param strig  $search_by
     * @return array
     */
    
    public function getJourneyUuid($base_api_url, string $search_by)
    {
        if (empty($base_api_url) && empty($qsearch_by)) {
            throw new Exception('Invalid arguments of method');
        }


        $client = HttpClient::create();
        $response = $client->request('GET', $base_api_url."/scale/decision-tree/journeys", [
            'query' => [
                'q' => $search_by,
            ]
        ]);

        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }

    /**
     * Get first set of Questions from Guide Match Journey
     *
     * @param string $base_api_url
     * @param string $journey_uuid - Journey instance id
     * @param string $question_uuid - Unique identifier of the question answered
     *
     * @return array
     */
    public function getQuestions($base_api_url, $journey_uuid, $questions_uuid)
    {
        if (empty($base_api_url) && empty($q)) {
            throw new Exception('Invalid arguments of method');
        }

        $client = HttpClient::create();
        $response = $client->request('GET', "{$base_api_url}/scale/decision-tree/journeys/{$journey_uuid}/questions/{$questions_uuid}");
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
    
    /**
     * Send to API endpoint the user answer and get the next questions or Comercial Agreements or a flag to call Suport
     * for further help
     *
     * @param string $base_api_url
     * @param string $journey_uuid  - Journey instance id
     * @param string $question_uuid - Unique identifier of the question answered in this response
     * @param array $question_response_id - Uuid question answered by user
     *
     * @return array
     */

    public function getDecisionTree(string $base_api_url, string $journey_uuid, string $question_uuid, array $question_response)
    {
        if (
            empty($base_api_url) &&
            empty($journey_uuid) &&
            empty($question_uuid) &&
            empty($question_response)
        ) {
            throw new Exception('Invalid arguments of method');
        }

        $data = [
            "data"=> $question_response
        ];

        $client = HttpClient::create();

        $response = $client->request('POST', "{$base_api_url}/scale/decision-tree/journeys/{$journey_uuid}/questions/{$question_uuid}/outcome", [
            'json' => $data
        ]);
        
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
    /**
     * Get summary of Guide Match Journey
     *
     * @param string $base_api_url
     * @param $journey_uuid - Journey instance id
     * @return array
     */

    public function getJourneySummary($base_api_url, $journey_uuid)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', "{$base_api_url}/ourney-summaries/{$journey_uuid}");
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}
