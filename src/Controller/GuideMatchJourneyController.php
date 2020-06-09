<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;

class GuideMatchJourneyController extends AbstractController
{
    public function journey(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $gPage)
    {
        $searchBy = $request->query->get('q');
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $response = [];

        if ($request->isMethod('post')) {
            if (!empty($request->request->get('uuid'))) {
                $response = !is_array($request->request->get('uuid')) ? [$request->request->get('uuid')] : $request->request->get('uuid');
            } else {
                $this->redirect($request->server->get('HTTP_REFERER'));
            }
        }
  
        $model = new GuideMatchJourneyModel($api);
        $model->getDecisionTree($journeyInstanceId, $questionUuid, $response);
        
        $nextPage  = $gPage+1;
        $historyPage = $gPage-2 <= 0 ? 0 : $gPage-2 ;
       
        $penultimateQuestion = $model->getHistoryQuestion($historyPage);
        $penultimateAnswers = $model->getHistoryAnswers($historyPage);
        $lastQuestionId = !empty($penultimateQuestion) ? $penultimateQuestion['id'] : '';
        $journeyHistory = $model->getJourneyHistory();

        $journeyHistoryAnswered = [
            'lastJourney' => $penultimateAnswers,
            'historyAnswered' => $journeyHistory
        ];

        $journeyHistoryEncode =  urlencode(json_encode($journeyHistoryAnswered));

        return $this->render('pages/guide_match_questions.html.twig', [
            'searchBy' => $searchBy,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'definedAnswers' => $model->getDefinedAnswers(),
            'answers' => [],
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'lastQuestionId' =>  $lastQuestionId,
            'journeyHistory' => $journeyHistoryEncode,
            'gPage' => $nextPage,
            'lastPage' =>  --$gPage
        ]);
    }
}
