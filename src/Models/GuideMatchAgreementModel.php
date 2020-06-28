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
                $nrAgreementSlots = count($agrement['lots']);
                foreach ($agrement['lots'] as $lot) {
                    $lotIndex = $nrAgreementSlots > 1 ? $lot['number']-1 : 0;
                    $lotNumber = !empty($agreementDetail['lots'][$lotIndex]['number']) ? $agreementDetail['lots'][$lotIndex]['number'] : null;
                    $lotDetails = $this->agreementApi->getLotDetails($agrement['number'], $lotNumber);
                    $this->lotsData[$agrement['number']][$lotNumber] =  $lotDetails;
                    $lotsTitle .= !empty($lotsTitle) ? ' or ' . $lotDetails['number'] .': '.$lotDetails['name'] : $lotDetails['number'] .': '.$lotDetails['name'];
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
