<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;
use App\Models\Encrypt;

use App\Models\UserAnswers;
use App\Models\GuideMatchAgreementModel;
use App\GuideMatchApi\ServiceAgreementsApi;


class GuideMatchJourneyResultController extends AbstractController
{
    public function journeyResult(Request $request, string $journeyId, string $journeyInstanceId, string $searchBy, string $journeyData)
    {
       
        $decrypt = new Decrypt(urldecode($journeyData));
        $journeyDataDecrypt = json_decode($decrypt->getDecryptedString(), true);
        $userAnswered = new UserAnswers($journeyDataDecrypt['historyAnswered']);

        $httpClient = HttpClient::create();
        $agreementsApi  = new ServiceAgreementsApi($httpClient, getenv('AGREEMENTS_SERVICE_ROOT_URL'));
        $agrementModel = new GuideMatchAgreementModel($agreementsApi, $journeyDataDecrypt['agreementData']);
        $frameworks = $agrementModel->getAgreements();
        $lots = $agrementModel->getLotsData();

        $encrypt = new Encrypt(json_encode($journeyDataDecrypt['historyAnswered']));
       
        $journeyHistory =  urlencode($encrypt->getEncryptedString());

        return $this->render('pages/result_page.html.twig', [
            'searchBy' => $searchBy,
            'historyAnswered' => $userAnswered->formatForView(),
            'frameworks' => $frameworks,
            'countFrameworks' => count($frameworks),
            'lotsData' => $lots,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'agreementsNames' => $agrementModel->getAgreementsNames(),
            'countLots' => $agrementModel->getCountLots()
            
        ]);
    }



}
