<?php

declare(strict_types=1);

namespace App\GuideMatchApi;

use Symfony\Component\HttpClient\HttpClient;
use \Exception;

class ServiceAgreementsApi
{

    /**
     * @param string $base_api_url
     * @param string $agreementId - Service Agreement ID
     * @return array
     */

    public function getServiceAgreement($base_api_url, $agreementId)
    {
        if (empty($base_api_url) && empty($qsearch_by)) {
            throw new Exception('Invalid arguments of method');
        }

        $client = HttpClient::create();
        $response = $client->request('GET', "{$base_api_url}/scale/agreements-service/agreements/{$agreementId}");
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }

    /**
     * @param string $base_api_url
     * @param string $agreementId - Service Agreement ID
     */
    public function getServiceAgreementsUpdated($base_api_url, $agreementId)
    {
        if (empty($base_api_url) && empty($qsearch_by)) {
            throw new Exception('Invalid arguments of method');
        }

        $client = HttpClient::create();
        $response = $client->request('GET', "{$base_api_url}/scale/agreements-service/agreements/{$agreementId}/updates");
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}
