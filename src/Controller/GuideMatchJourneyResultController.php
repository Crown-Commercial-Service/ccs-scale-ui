<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Encrypt;
use App\Models\Decrypt;
use App\Models\UserAnswers;
use App\Models\GuideMatchAgreementModel;
use App\GuideMatchApi\ServiceAgreementsApi;
use App\Models\GuideMatchResponseType;
use App\Models\GuideMatchJourneyHistoryModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Exception;

class GuideMatchJourneyResultController extends AbstractController
{
    public function journeyResult(string $journeyId, string $journeyInstanceId, string $agreements)
    {
        
        // get journey History
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $journeyHistoryModel = new GuideMatchJourneyHistoryModel($api, $journeyInstanceId);

        // get history answers
        $historyUserAnswers =  $journeyHistoryModel->getJourneyHistoryAnswers();
       
       
        if (empty($historyUserAnswers)) {
            throw new Exception('Wrong url data');
        }

        // format answers for view
        $userAnswers = new UserAnswers();
        $userAnswersFormatedForView = $userAnswers->formatForView($historyUserAnswers);

        $encrypt = new Encrypt(json_encode($historyUserAnswers));
       
        $journeyHistory =  urlencode($encrypt->getEncryptedString());
        $searchBy = $journeyHistoryModel->getSearchTerm();


        $isProduct = false;
        if ($journeyHistoryModel->getOutcomeType()  === GuideMatchResponseType::GuideMatchResponseSupport) {
            $isProduct = true;

            return $this->render('pages/result_page_product.html.twig', [
                    'searchBy' => $searchBy,
                    'historyAnswered' => $userAnswersFormatedForView,
                    'journeyId' => $journeyId,
                    'journeyInstanceId' => $journeyInstanceId,
                    'journeyHistory' => $journeyHistory,
                                    
                ]);
        }

        $decrypt = new Decrypt(urldecode($agreements));
        $agreementsData = json_decode($decrypt->getDecryptedString(), true);
       
        $httpClient = HttpClient::create();
        $agreementsApi  = new ServiceAgreementsApi($httpClient, getenv('AGREEMENTS_SERVICE_ROOT_URL'));
        $agrementModel = new GuideMatchAgreementModel($agreementsApi, $agreementsData);
        $frameworks = $agrementModel->getAgreements();
        $lots = $agrementModel->getLotsData();
        //dd($lots);die();
        return $this->render('pages/result_page.html.twig', [
            'searchBy' => $searchBy,
            'historyAnswered' => $userAnswersFormatedForView,
            'frameworks' => $frameworks,
            'countFrameworks' => count($frameworks),
            'lotsData' => $lots,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'agreementsNames' => $agrementModel->getAgreementsNames(),
            'countLots' => $agrementModel->getCountLots(),
            'isProduct' => $isProduct
            
        ]);
    }
}
