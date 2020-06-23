<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Models\GuideMatchJourneyModel;
use App\Models\GuideMatchResponseType;
use App\GuideMatchApi\GuideMatchJourneyApi;
use App\Models\Encrypt;
use App\Models\UserAnswersFormType\UserAnswerFormatFactory;

class GuideMatchJourneyController extends AbstractController
{
    public function journey(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $gPage)
    {
        $searchBy = $request->query->get('q');
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $userQuestionResponse = [];

        //get POST data
        $postData = $request->request->all();

        //get question form type to know, used to know how the answer will be handle to be send to API
        $formType = $postData['form-type'];

        $formatAnswerObject = UserAnswerFormatFactory::getFormTypeObject($formType, $postData);

        //get the answer correctly formeted to be send to API
        $userQuestionResponse = $formatAnswerObject->getAnswersFormated();

        if (empty($userQuestionResponse)) {

                /*
            TBD - Add server Validation
            $this->addFlash(
                'error',
                'You need to select something.'
            );
            // return $this->redirect($request->server->get('HTTP_REFERER'));
            */
        }
       
       
        $model = new GuideMatchJourneyModel($api);
        $model->getDecisionTree($journeyInstanceId, $questionUuid, $userQuestionResponse);

        $apiResponseType = $model->getApiResponseType();
        $journeyHistory = $model->getJourneyHistory();

        //redirect to journey result page
        if (
            $apiResponseType == GuideMatchResponseType::GuideMatchResponseSupport ||
            $apiResponseType == GuideMatchResponseType::GuideMatchResponseAgreement
        ) {
            return $this->redirectToResultsPage($journeyId, $journeyInstanceId);
        }

        // go to the next question of Journey
        return $this->journeyNextQuestion($model, $searchBy, $journeyId, $journeyInstanceId, $gPage, $journeyHistory);
    }


    /**
     *redirect to result page if we have reached to the end of journey
     *
     * @param string $journeyId
     * @param string $journeyInstanceId
     * @param GuideMatchJourneyModel $model
     * @return void
     */
    private function redirectToResultsPage(string $journeyId, string $journeyInstanceId)
    {
        return  $this->redirectToRoute("journey-result", [
            'journeyId' => $journeyId,
            'journeyInstanceId'=>$journeyInstanceId,
        ]);
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
    private function journeyNextQuestion(GuideMatchJourneyModel $model, string $searchBy, string $journeyId, string $journeyInstanceId, string $gPage, array $journeyHistory)
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
            'userAnswers' => [],
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'lastQuestionId' =>  $lastQuestionId,
            'journeyHistory' => $journeyHistoryEncode,
            'gPage' => $nextPage,
            'lastPage' => $lastPage
        ]);
    }
}
