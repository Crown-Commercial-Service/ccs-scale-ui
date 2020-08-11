<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use \Exception;

class GuideMatchGetJourneysApi
{
    protected $httpClient;
    protected $baseApiUrl;

    public function __construct(HttpClientInterface $httpClient, string $baseApiUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
    }

    /**
     * @param string $search_by
     * @return array
     */

    public function searchJourneys( string $search_by)
    {

        if (empty($search_by)) {
            throw new Exception('Invalid arguments of method');
        }
        
        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/guided-match-service/search-journeys/{$search_by}", [
            'headers' => ['x-api-key' => getenv('AGREEMENTS_SERVICE_API_KEY')],
        ]);
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            return [];
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
        return $content;
    }

   
}
