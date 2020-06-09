<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use \Exception;

class GuideMatchJourneyApi
{
    private $httpClient;
    private $baseApiUrl;

    public function __construct(CurlHttpClient $httpClient, string $baseApiUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
    }

    /**Í
     * Start Guide Match Journey
     *
     * @param strig  $search_by
     * @return array
     */
    
    public function getJourneyUuid(string $search_by)
    {
        if (empty($search_by)) {
            throw new Exception('Invalid arguments of method');
        }
     
        $response = $this->httpClient->request('GET', $this->baseApiUrl."/scale/decision-tree/journeys", [
            'query' => [
                'q' => $search_by,
            ]
        ]);

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
       
        return $content;
    }


    /**
     *
     * @param string $searchBy
     * @param string $journeyId
     * @return void
     */
    public function startJourney(string $searchBy, string $journeyId)
    {
        if (empty($searchBy)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('POST', $this->baseApiUrl."/scale/guided-match-service/journeys/{$journeyId}", [
            'json' => ['searchTerm' => $searchBy]
        ]);

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
       
        return $content;
    }
    /**
     * Get first set of Questions from Guide Match Journey
     *
     * @param string $$this->baseApiUrl
     * @param string $journey_uuid - Journey instance id
     * @param string $question_uuid - Unique identifier of the question answered
     *
     * @return array
     */
    public function getQuestions($journeyUuid, $questionsUuid)
    {
        if (empty($journeyUuid) && empty($questionsUuid)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/decision-tree/journeys/{$journeyUuid}/questions/{$questionsUuid}");
       
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
        return $content;
    }
    
    /**
     * Send to API endpoint the user answer and get the next questions or Comercial Agreements or a flag to call Suport
     * for further help
     *
     * @param string $$this->baseApiUrl
     * @param string $journey_uuid  - Journey instance id
     * @param string $question_uuid - Unique identifier of the question answered in this response
     * @param array $question_response_id - Uuid question answered by user
     *
     * @return array
     */

    public function getDecisionTree(string $journeyUuid, string $questionsUuid, array $questionResponse)
    {
        if (
            empty($journeyUuid) &&
            empty($questionsUuid) &&
            empty($questionResponse)
        ) {
            throw new Exception('Invalid arguments of method');
        }
       
        $data = [
            "id"=> $questionsUuid,
            'answers' => $questionResponse
        ];
        
        $response = $this->httpClient->request('POST', "{$this->baseApiUrl}/scale/guided-match-service/journey-instances/{$journeyUuid}/questions/{$questionsUuid}", [
            'json' => [$data]
        ]);
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
       
        return $content;
    }
    /**
     * Get summary of Guide Match Journey
     *
     * @param string $$this->baseApiUrl
     * @param $journey_uuid - Journey instance id
     * @return array
     */

    public function getJourneySummary($journeyUuid)
    {
        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/journey-summaries/{$journeyUuid}");

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
        return $content;
    }
}
