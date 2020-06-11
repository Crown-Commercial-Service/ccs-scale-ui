<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Component\HttpClient\CurlHttpClient;
use \Exception;

class ServiceAgreementsApi
{

    private $httpClient;
    private $baseApiUrl;

    public function __construct(CurlHttpClient $httpClient, string $baseApiUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
    }


    /**
     * @param string $agreementId - Service Agreement ID
     * @return array
     */

    public function getServiceAgreement($agreementId)
    {
        if (empty($agreementId)) {
            throw new Exception('Invalid arguments of method');
        }
        
        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}");
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            return [];
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
       
        
        return $content;
    }

    /**
     * @param string $agreementId - Service Agreement ID
     */
    public function getServiceAgreementsUpdated($agreementId)
    {
        if (empty($agreementId)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/updates");

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
       
        return $content;
    }

    public function getLotDetails($agreementId, $lot){

        if (empty($agreementId) && empty($lot)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/lots/{$lot}");

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            return null;
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
        return $content;
    }
}
