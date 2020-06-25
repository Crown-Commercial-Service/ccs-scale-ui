<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Component\HttpClient\CurlHttpClient;

use \Exception;

class GuideMatchJourneyApi
{
    protected $httpClient;
    protected $baseApiUrl;

    public function __construct(CurlHttpClient $httpClient, string $baseApiUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
    }

    /**
     * Start Guide Match Journey
     *
     * @param strig  $searchBy
     * @return array
     */
    
    public function getJourneyUuid(string $searchBy)
    {
        if (empty($searchBy)) {
            throw new Exception('Invalid arguments of method');
        }
     
        $response = $this->httpClient->request('GET', $this->baseApiUrl."/scale/decision-tree/journeys", [
            'headers' => [
                'x-api-key' => getenv('GUIDED_MATCH_SERVICE_API_KEY'),
            ],
            'query' => [
                'q' => $searchBy,
            ]
        ]);

        return $this->handleApiResponse($response);
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

        return $this->handleApiResponse($response);
    }

    /**
     * Get first set of Questions from Guide Match Journey
     *
     * @param string $baseApiUrl
     * @param string $journeyUuid - Journey instance id
     * @param string $questionsUuid - Unique identifier of the question answered
     *
     * @return array
     */
    public function getQuestions($journeyUuid, $questionsUuid)
    {
        if (empty($journeyUuid) && empty($questionsUuid)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/decision-tree/journeys/{$journeyUuid}/questions/{$questionsUuid}",[
            'headers' => [
                'x-api-key' => getenv('GUIDED_MATCH_SERVICE_API_KEY'),
            ],
        ]);
       
        return $this->handleApiResponse($response);
    }
    
    /**
     * Send to API endpoint the user answer and get the next questions or Comercial Agreements or a flag to call Suport
     * for further help
     *
     * @param string $baseApiUrl
     * @param string $journeyUuid  - Journey instance id
     * @param string $questionsUuid - Unique identifier of the question answered in this response
     * @param array  $questionResponse - Uuid question answered by user
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
            'headers' => [
                'x-api-key' => getenv('GUIDED_MATCH_SERVICE_API_KEY'),
            ],
            'json' => [$data]
        ]);

        return $this->handleApiResponse($response);
    }
   
    /**
     * Returns the journey history (all questions and answers) for the specified journey-instance.
     * Includes Question and Answer texts as displayed to the user
     *
     * @param string $journeyUuid
     * @return array
     */
    public function getJourneyHistory(string $journeyUuid)
    {
        if (empty($journeyUuid)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/guided-match-service/journey-instances/{$journeyUuid}",[
            'headers' => [
                'x-api-key' => getenv('GUIDED_MATCH_SERVICE_API_KEY'),
            ],
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
     * Get get details for sa specific questions from journey
     *
     * @param string $journeyUuid
     * @param string $questionsUuid
     * @return array
     */
    public function getJourneyQuestion(string $journeyUuid, string $questionsUuid)
    {
        if (
            empty($journeyUuid) &&
            empty($questionsUuid)
            ) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/guided-match-service/journey-instances/{$journeyUuid}/questions/{$questionsUuid}",[
            'headers' => [
                'x-api-key' => getenv('GUIDED_MATCH_SERVICE_API_KEY'),
            ],
        ]);

        return $this->handleApiResponse($response);
    }


    private function handleApiResponse($response)
    {
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
     
        return $content;
    }
}
