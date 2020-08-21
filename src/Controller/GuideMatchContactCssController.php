<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Models\UserAnswers;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;
use Symfony\Component\HttpClient\HttpClient;
use App\GuideMatchApi\GuideMatchGetJourneysApi;
use App\Models\JourneysModel;



class GuideMatchContactCssController extends AbstractController
{
    public function contactCss(Request $request, $journeyId, $journeyInstanceId,$questionUuid, $journeyHistory, $gPage)
    {

        $searchBy = $request->query->get('q');
        $resultPage = $request->query->get('resultPage');
        $agreements = $request->query->get('agreements');

        $userAnswersFormatedForView = [];
        $userAnswers = new UserAnswers();
        if ($journeyHistory != '0') {
            $decrypt = new Decrypt(urldecode($journeyHistory));
            $historyUserAnswers = json_decode($decrypt->getDecryptedString(), true);
            $userAnswersFormatedForView = $userAnswers->formatForView($historyUserAnswers, $questionUuid);
        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchGetJourneysApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $journeysModel = new JourneysModel($api, $searchBy);
        $journeys = $journeysModel->getJourneys();

        $journeysPage =  false;
        if (count($journeys) > 1) {
            
            $selectedJourney =  $userAnswers->addSelectedJourneyToUserAnswers($searchBy,$journeyId,$journeys);
            array_unshift($userAnswersFormatedForView,$selectedJourney);
            $journeysPage = true;
        }

       
        return $this->render('pages/contact_css.html.twig', [
           
            'searchBy' => $searchBy,
            'searchByEncoded' => rawurlencode($searchBy),
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'lastPage' => $gPage,
            'lastQuestionId' => $questionUuid,
            'pageTitle' => 'Contact CCS',
            'historyAnswered' => $userAnswersFormatedForView,
            'redirectToResultPage' => $resultPage,
            'agreements' => !empty($agreements) ? urlencode($agreements) : '',
            'journeysPage' => $journeysPage
          
        ]);
        
    }
}
