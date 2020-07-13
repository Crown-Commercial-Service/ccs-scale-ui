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
use App\Models\QuestionsValidators\ValidatorsFactory;
use App\Models\UserAnswers;
use Exception;

class GuideMatchJourneyController extends AbstractController
{
    public function journey(Request $request, $journeyId, $journeyInstanceId, $questionUuid, $gPage)
    {
        $searchBy = $request->query->get('q');
        $csfrToken = $request->request->get('token');

        if(empty($searchBy) && !$this->isCsrfTokenValid('save-answers', $csfrToken)){
            throw new Exception('Invalid request');
        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $userQuestionResponse = [];
        $model = new GuideMatchJourneyModel($api);

        //get POST data
        $postData = $request->request->all();
        $formType = !empty($postData['form-type']) ? $postData['form-type'] : '';

        if (empty($formType)) {
            throw new Exception('Form type is missing');
        }

        $validate = $this->validateUserAnswer($formType, $postData);

 
        if (!$validate->isValid()) {
            $this->addFlash('error', '');
                
            $lastQuestionId =  !empty($postData['lastQuestionId']) ? $postData['lastQuestionId'] : '';
            $journeyHistory = !empty($postData['journeyHistory']) ? $postData['journeyHistory'] : '';
           
            // get question
            $model->setQuestionDetails($journeyInstanceId, $questionUuid);

            $definedAnwers = $model->getDefinedAnswers();

            $userAnswer =  new UserAnswers();
            $formatAnswers = $userAnswer->getFormatUserAnswers($postData,$definedAnwers);
            $questionText = $model->getText();
       
            return $this->render('pages/guide_match_questions.html.twig', [
                'searchBy' => $searchBy,
                'journeyId' => $journeyId,
                'journeyInstanceId' => $journeyInstanceId,
                'definedAnswers' => $definedAnwers,
                'userAnswers' => $formatAnswers,
                'uuid' => $model->getUuid(),
                'text' => $questionText,
                'type' => $model->getType(),
                'hint' => $model->getHint(),
                'lastQuestionId' =>  $lastQuestionId,
                'journeyHistory' => $journeyHistory,
                'gPage' => $gPage,
                'lastPage' => $gPage-1,
                'errorMessage' => $validate->getErrorMessage(),
                'pageTitle' => $questionText
            ]);
        }

        //get question form type to know, used to know how the answer will be handle to be send to API
        $formType = $postData['form-type'];

        if (empty($formType)) {
            throw new Exception('Invalid request');
        }

        $formatAnswerObject = UserAnswerFormatFactory::getFormTypeObject($formType, $postData);

        //get the answer correctly formeted to be send to API
        $userQuestionResponse = $formatAnswerObject->getAnswersFormated();
       
       
        $model->getDecisionTree($journeyInstanceId, $questionUuid, $userQuestionResponse);

        $apiResponseType = $model->getApiResponseType();
        $journeyHistory = $model->getJourneyHistory();

        //redirect to journey result page
        if (
            $apiResponseType == GuideMatchResponseType::GuideMatchResponseSupport ||
            $apiResponseType == GuideMatchResponseType::GuideMatchResponseAgreement
        ) {
            return $this->redirectToResultsPage($model, $journeyId, $journeyInstanceId);
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
    private function redirectToResultsPage(GuideMatchJourneyModel $model, string $journeyId, string $journeyInstanceId)
    {
        $agreementData = $model->getAgreementData();
        $agreementDataEncoded = '';
       
        if (!empty($agreementData)) {
            $agreementDataJson = json_encode($agreementData);
            $encrypt = new Encrypt($agreementDataJson);
            $agreementDataEncoded =  urlencode($encrypt->getEncryptedString());
        }

        return  $this->redirectToRoute("journey-result", [
            'journeyId' => $journeyId,
            'journeyInstanceId'=>$journeyInstanceId,
            'agreements' => $agreementDataEncoded
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
        $questionText = $model->getText();
       
        return $this->render('pages/guide_match_questions.html.twig', [
            'searchBy' => $searchBy,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'definedAnswers' => $model->getDefinedAnswers(),
            'userAnswers' => [],
            'uuid' => $model->getUuid(),
            'text' => $questionText,
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'lastQuestionId' =>  $lastQuestionId,
            'journeyHistory' => $journeyHistoryEncode,
            'gPage' => $nextPage,
            'lastPage' => $lastPage,
            'pageTitle' => $questionText
        ]);
    }

    private function validateUserAnswer(string $formType, array $userAnswer)
    {
        return ValidatorsFactory::getValidator($formType, $userAnswer);
    }
}
