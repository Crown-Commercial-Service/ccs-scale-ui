<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\Models\GuideMatchResponseType;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Encrypt;
use Symfony\Component\HttpFoundation\Response;

class GuideMatchJourneyController extends AbstractController
{
    public function journey(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $gPage)
    {
        $searchBy = $request->query->get('q');
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $userQuestionResponse = [];

        if ($request->isMethod('post')) {
            if (!empty($request->request->get('uuid'))) {
                $userQuestionResponse = !is_array($request->request->get('uuid')) ? [$request->request->get('uuid')] : $request->request->get('uuid');
            } else {
             
                /*
                TBD - Add server Validation
                $this->addFlash(
                    'error',
                    'You need to select something.'
                );
               // return $this->redirect($request->server->get('HTTP_REFERER'));
               */
            }
        }
        $model = new GuideMatchJourneyModel($api);
        $model->getDecisionTree($journeyInstanceId, $questionUuid, $userQuestionResponse);

        $apiResponseType = $model->getApiResponseType();
        $journeyHistory = $model->getJourneyHistory();
        //redirect to journey result page
        if ($apiResponseType == GuideMatchResponseType::GuideMatchResponseAgreement) {
            $journeyData = json_encode([
                'agreementData' => $model->getAgreementData(),
                'historyAnswered' => $journeyHistory
            ]);


            $encrypt = new Encrypt($journeyData);
            $journeyDataEncode =  urlencode($encrypt->getEncryptedString());

            return  $this->redirectToRoute("journey-result", [
                'journeyId' => $journeyId,
                'journeyInstanceId'=>$journeyInstanceId,
                'searchBy' => $searchBy,
                'journeyData' => $journeyDataEncode

            ]);
        }

        return $this->questionResponse($model, $searchBy, $journeyId, $journeyInstanceId ,$gPage, $journeyHistory);
    }

    /**
     * hadle questions journey response
     *
     * @param GuideMatchJourneyModel $model
     * @param string $searchBy
     * @param string $journeyId
     * @param string $journeyInstanceId
     * @param string $gPage
     * @param array $journeyHistory
     * @return Response
     */
    private function questionResponse(GuideMatchJourneyModel $model, string $searchBy, string $journeyId, string $journeyInstanceId ,string $gPage, array $journeyHistory)
    {
       
        $nextPage  = $gPage + 1;
        $lastPage = $gPage - 1;

        $lastQuestion = $model->getHistoryQuestion($lastPage);
        $lastQuestionId = !empty($lastQuestion) ? $lastQuestion['id'] : '';

        $journeyHistoryAnsweredJson = json_encode($journeyHistory);

        $encrypt = new Encrypt($journeyHistoryAnsweredJson);
        $journeyHistoryEncode =  urlencode($encrypt->getEncryptedString());
       
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
