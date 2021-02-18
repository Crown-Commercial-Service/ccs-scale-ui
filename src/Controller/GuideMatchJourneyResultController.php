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
use App\GuideMatchApi\GuideMatchGetJourneysApi;
use App\Models\JourneysModel;


use Exception;

class GuideMatchJourneyResultController extends AbstractController
{

    private $pageTitle = 'Result Journey Page';

    public function journeyResult(string $journeyId, string $journeyInstanceId, $agreements=null)
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

        $lastQuestion = end($historyUserAnswers);
        $lastQuestionId = $lastQuestion['question']['id'];
        $lastPage = count($historyUserAnswers) - 1;

        // format answers for view
        $userAnswers = new UserAnswers();
        $userAnswersFormatedForView = $userAnswers->formatForView($historyUserAnswers);

        $encrypt = new Encrypt(json_encode($historyUserAnswers));
       
        $journeyHistory =  urlencode($encrypt->getEncryptedString());
        $searchBy = $journeyHistoryModel->getSearchTerm();

        $httpClient = HttpClient::create();
        $api = new GuideMatchGetJourneysApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $journeysModel = new JourneysModel($api, $searchBy);
        $journeys = $journeysModel->getJourneys();

        $journeysPage =  false;
        if (count($journeys) > 1) {
                
            $selectedJourney =  $userAnswers->addSelectedJourneyToUserAnswers($searchBy,$journeyId,$journeys);
            array_unshift($userAnswersFormatedForView,$selectedJourney);
            $journeysPage =  true;
        }

        $isProduct = false;
        if ($journeyHistoryModel->getOutcomeType()  === GuideMatchResponseType::GuideMatchResponseSupport) {
           
            $isProduct = true;
            return $this->render('pages/result_page_product.html.twig', [
                'searchBy' => $searchBy,
                'searchByEncoded' => rawurlencode($searchBy),
                'historyAnswered' => $userAnswersFormatedForView,
                'journeyId' => $journeyId,
                'journeyInstanceId' => $journeyInstanceId,
                'journeyHistory' => $journeyHistory,
                'lastPage' => $lastPage,
                'lastQuestionId' => $lastQuestionId,
                'pageTitle' => $this->pageTitle,
                'journeysPage' => $journeysPage
            ]);
        }

        $decrypt = new Decrypt(urldecode($agreements));
        $agreementsData = json_decode($decrypt->getDecryptedString(), true);
        $httpClient = HttpClient::create();
        $agreementsApi  = new ServiceAgreementsApi($httpClient, getenv('AGREEMENTS_SERVICE_ROOT_URL'));
        $agrementModel = new GuideMatchAgreementModel($agreementsApi, $agreementsData);
        $frameworks = $agrementModel->getAgreements();
        $lots = $agrementModel->getLotsData();

        return $this->render('pages/result_page.html.twig', [
            'searchBy' => $searchBy,
            'searchByEncoded' => rawurlencode($searchBy),
            'historyAnswered' => $userAnswersFormatedForView,
            'frameworks' => $frameworks,
            'countFrameworks' => count($frameworks),
            'lotsData' => $lots,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'lastQuestionId' => $lastQuestionId,
            'agreementsNames' => $agrementModel->getAgreementsNames(),
            'countLots' => $agrementModel->getCountLots(),
            'isProduct' => $isProduct,
            'isScale' => $agrementModel->getScale(),
            'lastPage' => $lastPage,
            'agreements' => $agreements,
            'pageTitle' => $this->pageTitle,
            'journeysPage' => $journeysPage
        ]);
    }
}
