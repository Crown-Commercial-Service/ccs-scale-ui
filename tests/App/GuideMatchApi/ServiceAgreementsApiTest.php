<?php

namespace App\Tests\App\GuideMatchyApi;

use PHPUnit\Framework\TestCase;
use App\GuideMatchApi\ServiceAgreementsApi;

class ServiceAgreementsTest extends TestCase
{
    public function testGetServiceAgreement()
    {
        $serviceAgreementId = 'RM6008';

        $api = new ServiceAgreementsApi();
        $base_api_url =  getenv('GUIDE_MATCH_DECISION_TREE_API');
        
        $response = $api->getServiceAgreement($base_api_url, $serviceAgreementId);
        $this->assertTrue(!empty($response));
        $this->assertArrayHasKey('number', $response);
        $this->assertArrayHasKey('detailUrl', $response);
        $this->assertArrayHasKey('lots', $response);
    }

    
    public function testGetServiceAgreementUpdated()
    {
        $serviceAgreementId = 'RM6008';

        $api = new ServiceAgreementsApi();
        $base_api_url =  getenv('GUIDE_MATCH_DECISION_TREE_API');
        
        $response = $api->getServiceAgreementsUpdated($base_api_url, $serviceAgreementId);
        $this->assertTrue(!empty($response));
        $this->assertTrue(is_array($response));

        foreach ($response as $erviceUpdated) {
            $this->assertArrayHasKey('date', $erviceUpdated);
            $this->assertArrayHasKey('url', $erviceUpdated);
            $this->assertArrayHasKey('text', $erviceUpdated);
        }
    }
}
