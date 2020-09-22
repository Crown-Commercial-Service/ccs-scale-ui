<?php


declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\ServiceAgreementsApi;

class GuideMatchAgreementModel
{
    private $agreementApi;
    private $agreements=[];
    private $countLots = 0;
    private $lotsData = [];
    private $agreementsNames = [];
    private $scale = true;

    /**
     *
     * @param ServiceAgreementsApi $agreementApi
     * @param array $agreementsData that was result from Guide Match Journey
     */
    public function __construct(ServiceAgreementsApi $agreementApi, array $agreementsData)
    {
        $this->agreementApi = $agreementApi;
        $this->setAgreements($agreementsData);
    }


    public function getAgreements()
    {
        return $this->agreements;
    }

    /**
     * Parse aggrements and format them for view
     *
     * @param array $agreementsData
     * @return void
     */
    private function setAgreements(array $agreementsData)
    {
        foreach ($agreementsData as $agrement) {
            $agreementDetail = $this->agreementApi->getServiceAgreement($agrement['number']);
            if (empty($agreementDetail)) {
                continue;
            }
            $this->countLots += !empty($agrement['lots']) ? count($agrement['lots']) : 0;
            $lotsTitle = '';
           
            if (!empty($agrement['lots'])) {
                foreach ($agrement['lots'] as $lot) {
                    $lotNumber = 'Lot '. $lot['number'];
                    $lotDetails = $this->agreementApi->getLotDetails($agrement['number'], 'Lot '. $lot['number']);
                    $this->lotsData[$agrement['number']][$lotNumber] =  $lotDetails;
                    $lotsTitle .= !empty($lotsTitle) ? ' or ' . $lotDetails['number'] .': '.$lotDetails['name'] : $lotDetails['number'] .': '.$lotDetails['name'];
                    $this->scale = isset($lot['scale']) && !$lot['scale'] ? $lot['scale'] : $this->scale;
                }
            }

            $this->agreementsNames[] = [
                'name' => $agreementDetail['name'],
                'number' => $agrement['number'],
                'lotsTitle' => $lotsTitle
                ];

            $agreementDetail['lotsTitle'] = $lotsTitle;
            $this->agreements[$agrement['number']] =  $agreementDetail;
        }
    }


    public function orderedLotsData(array $lotsData){

        if (!empty($lotsData)) {
            foreach ($lotsData as $agreementKey => $lots) {
            
                uksort($lots, function ($a, $b) {
                    return strnatcmp($a, $b);
                });

                $lotsDataOrdered[$agreementKey] = $lots;
            }

            return $lotsDataOrdered;
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

    /**
     * Getter method for scale
     *
     * @return bolean
     */
    public function getScale()
    {
        return $this->scale;
    }
}
