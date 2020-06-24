<?php


declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\ServiceAgreementsApi;

class GuideMatchAgreementModel
{
    private $agreementApi;
    private $agreements=[];
    private $countLots = 0;
    private $lostNumbers = [];
    private $lotsData = [];
    private $agreementsNames = [];

    public function __construct(ServiceAgreementsApi $agreementApi, array $agreementsIds)
    {
        $this->agreementApi = $agreementApi;
        $this->setAgreements($agreementsIds);
        $this->setLotsData();
    }


    public function getAgreements()
    {
        return $this->agreements;
    }


    private function setAgreements(array $agreementsIds)
    {
        foreach ($agreementsIds as $agrementId) {
            $response = $this->agreementApi->getServiceAgreement($agrementId['number']);
            
            if (empty($response)) {
                continue;
            }
            $this->countLots += !empty($response['lots']) ? count($response['lots']) : 0;

            $lotsTitle = '';
            if (!empty($response['lots'])) {
                foreach ($response['lots'] as $lot) {
                    $lotsTitle .= !empty($lotsTitle) ? ' or ' . $lot['number'] .': '.$lot['name'] : $lot['number'] .': '.$lot['name'];
                    $this->lostNumbers[$agrementId['number']][] = $lot['number'];
                }
            }

            $this->agreementsNames[] = [
                'name' => $response['name'],
                'number' => $agrementId['number'],
                'lotsTitle' => $lotsTitle
                ];
            $response['lotsTitle'] = $lotsTitle;
            $this->agreements[$agrementId['number']] =  $response;
        }
    }

    private function setLotsData()
    {
        foreach ($this->lostNumbers as $agreementId => $lots) {
            foreach ($lots as $lot) {

                $response = $this->agreementApi->getLotDetails($agreementId, $lot);
                if (empty($response)) {
                    continue;
                }

                $this->lotsData[$agreementId][$response['number']] =  $response;
            }
        }
    }

    public function getLotsData()
    {
        return $this->lotsData;
    }

    public function getAgreementsNames()
    {
        return $this->agreementsNames;
    }

    public function getCountLots()
    {
        return $this->countLots;
    }
}
