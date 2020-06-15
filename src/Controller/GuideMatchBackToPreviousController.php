<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Encrypt;
use App\Models\Decrypt;
use App\Models\UserAnswers;

class GuideMatchBackToPreviousController extends AbstractController
{
    public function backToPrevious(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $journeyHistory, $gPage)
    {
        $searchBy = $request->query->get('q');
        $changeAnswer = $request->query->get('changeAnswer');


        $decrypt = new Decrypt(urldecode($journeyHistory));
        $response = json_decode($decrypt->getDecryptedString(), true);
       
        $anwers  =  !empty($response['lastJourney']) ? $response['lastJourney'] : [];

        if (empty($anwers)) {

            $userAnswered = new UserAnswers([]);
            //get answer from history
            $anwers =  $userAnswered->getAnswersFromHistory($response,$gPage);
       
        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $model = new GuideMatchJourneyModel($api);
       
        $lastPage = $gPage -2 > 1 ? $gPage -2 : 0;
        $nextPage = $gPage +1;

        $model->getDecisionTree($journeyInstanceId, $questionUuid, $anwers);
        
        $penultimateQuestion = $model->getHistoryQuestion($lastPage);
        $penultimateAnswers = $model->getHistoryAnswers($lastPage);
        $lastQuestionId = !empty($penultimateQuestion) ? $penultimateQuestion['id'] : '';

        $journeyHistory = $model->getJourneyHistory();

        $journeyHistoryAnswered = [
            'lastJourney' => $penultimateAnswers,
            'historyAnswered' => $journeyHistory
        ];
        

        $journeyHistoryAnsweredJson = json_encode($journeyHistoryAnswered);
        $encrypt = new Encrypt($journeyHistoryAnsweredJson);
        $journeyHistoryEncode =  urlencode($encrypt->getEncryptedString());
    

        if ($gPage < 1) {
            $model = new GuideMatchJourneyModel($api);
            $model->startJourney($journeyId, $searchBy);
        }
        $questionId =  $model->getUuid();

        $answers= [];

      
         if (empty($response['historyAnswered'])) {
            $historyAnswered = $response;
        }else{
            $historyAnswered = $response['historyAnswered'];
        }
        if (!$changeAnswer==1) {
            $answers = $model->getQuestionAnswers($questionId, $historyAnswered);
        }

        return $this->render('pages/guide_match_questions.html.twig', [
            'searchBy' => $searchBy,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'definedAnswers' => $model->getDefinedAnswers(),
            'answers' => $answers,
            'uuid' => $questionId,
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'lastQuestionId' =>  $lastQuestionId,
            'journeyHistory' => $journeyHistoryEncode,
            'gPage' => $nextPage,
            'lastPage' => --$gPage
        ]);
    }
}

