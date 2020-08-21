<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;

class GuideMatchBackToPreviousController extends AbstractController
{
    public function backToPrevious(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $journeyHistory, $gPage)
    {
        $searchBy = $request->query->get('q');
        $changeAnswer = $request->query->get('changeAnswer');

        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
        $model = new GuideMatchJourneyModel($api);

        // Decript journey history answers
        $decrypt = new Decrypt(urldecode($journeyHistory));
        $journeyHistoryData = json_decode($decrypt->getDecryptedString(), true);
        $model->setQuestionDetails($journeyInstanceId, $questionUuid);

        $lastPage = $gPage-1 > 0 ? $gPage-1 : 0;
        $nextPage = $gPage+1;
       
        $lastQuestionId = $journeyHistoryData[$lastPage]['question']['id'];

        $questionId = $model->getUuid();

        // Go back to the first page of journey
        if ($gPage < 1) {
            $model = new GuideMatchJourneyModel($api);
            $model->startJourney($journeyId, $searchBy);
        }

        $userAnswers= [];
        if (!$changeAnswer==1) {
            $userAnswers = $model->getQuestionAnswers($questionId, $journeyHistoryData);
        }
//dump($model->getDefinedAnswers());
 //       dd($userAnswers);
        $questionText = $model->getText();
        return $this->render('pages/guide_match_questions.html.twig', [
            'searchBy' => $searchBy,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'definedAnswers' => $model->getDefinedAnswers(),
            'userAnswers' => $userAnswers,
            'uuid' => $questionId,
            'text' =>$questionText,
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'lastQuestionId' =>  $lastQuestionId,
            'journeyHistory' => $journeyHistory,
            'gPage' => $nextPage,
            'lastPage' => $lastPage,
            'pageTitle' => $questionText,
            'currentPage' => $gPage
        ]);
    }
}
