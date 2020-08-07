<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use \Exception;

class ServiceAgreementsApi
{
    protected $httpClient;
    protected $baseApiUrl;

    public function __construct(HttpClientInterface $httpClient, string $baseApiUrl)
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


        //$uri = "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}";
        //$time_start = microtime(true); 



        if (empty($agreementId)) {
            throw new Exception('Invalid arguments of method');
        }
        
        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}", [
            'headers' => ['x-api-key' => getenv('AGREEMENTS_SERVICE_API_KEY')],
        ]);
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            return [];
            throw new Exception('Invalid API response:'.$e->getMessage());
        }

        //$time_end = microtime(true); 
        //$duration = $time_end - $time_start; 
        //error_log("Speed Test GM-API: $uri :::: $duration \n", 3,'../public/speedTest.log');
       
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

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/updates", [
            'headers' => [
                'x-api-key' => getenv('AGREEMENTS_SERVICE_API_KEY'),
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

    public function getLotDetails($agreementId, $lot)
    {

        //$uri = "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/lots/{$lot}";
        //$time_start = microtime(true); 

        if (empty($agreementId) && empty($lot)) {
            throw new Exception('Invalid arguments of method');
        }

        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/lots/{$lot}", [
            'headers' => [
                'x-api-key' => getenv('AGREEMENTS_SERVICE_API_KEY'),
            ],
        ]);
       
        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            return null;
            throw new Exception('Invalid API response:'.$e->getMessage());
        }

        //$time_end = microtime(true); 
        //$duration = $time_end - $time_start; 
        //error_log("Speed Test GM-API: $uri :::: $duration \n", 3,'../public/speedTest.log');
        return $content;
    }

    public function getLotSupliers(string $agreementId, string $lotNumber)
    {
        //$uri = "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/lots/{$lotNumber}/suppliers";
        //$time_start = microtime(true); 


        $response = $this->httpClient->request('GET', "{$this->baseApiUrl}/scale/agreements-service/agreements/{$agreementId}/lots/{$lotNumber}/suppliers", [
        'headers' => [
            'x-api-key' => getenv('AGREEMENTS_SERVICE_API_KEY'),
        ],
    ]);

        try {
            $content = $response->getContent();
            $content = $response->toArray();
        } catch (Exception $e) {
            throw new Exception('Invalid API response:'.$e->getMessage());
        }
   
        //$time_end = microtime(true); 
        //$duration = $time_end - $time_start; 
        //error_log("Speed Test GM-API: $uri :::: $duration \n", 3,'../public/speedTest.log');

        return $content;
    }
}
