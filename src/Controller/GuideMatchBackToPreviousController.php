<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class GuideMatchBackToPreviousController extends AbstractController
{
    public function backToPrevious(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $journeyHistory, $gPage)
    {
        $searchBy = $request->query->get('q');
        $response =  json_decode(urldecode($journeyHistory), true);

        $anwers  =  !empty($response['lastJourney']) ? $response['lastJourney'] : [];

        if (empty($anwers)) {
            throw new Exception('Last answers are missing');
        }


        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));
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

        $journeyHistoryEncode =  urlencode(json_encode($journeyHistoryAnswered));

        if ($gPage< 1) {
            $model = new GuideMatchJourneyModel($api);
            $model->startJourney($journeyId, $searchBy);
        }
        $questionId =  $model->getUuid();

        $answers = $model->getQuestionAnswers($questionId, $response['historyAnswered']);

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
