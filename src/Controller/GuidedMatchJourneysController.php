<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use App\GuideMatchApi\GuideMatchGetJourneysApi;
use App\Models\JourneysModel;
use App\Models\QuestionsValidators\ValidatorsFactory;
use Exception;


class GuidedMatchJourneysController extends AbstractController{


     private $pageTitle = 'Select a Journey';

     //simulate an API error message
     private $errorMessage  = [
        [
            "failureValidationTypeCode" => 'noSelection',
            "errorMessage" => 'Select which area suits your requirements'
        ] 
    ];

    public function searchJourneys(Request $request){

        $searchBy = $request->query->get('q');
        $journeyId = $request->query->get('journeyId');
        $showError = null;
        
        if(empty($searchBy)){
            throw new Exception('Invalid request'); 
        }

        if($request->getMethod() === "POST"){

            $postData = $request->request->all();
            $formType = !empty($postData['form-type']) ? $postData['form-type'] : '';

            $validate = $this->validateUserAnswer($formType, $postData);
            $csfrToken = $request->request->get('token');
            $journeyId = $request->request->get('uuid');
            $journeyId = str_replace('/', '', $journeyId);
            $searchBy = $request->request->get('searchBy');

            if( empty($journeyId) && !$this->isCsrfTokenValid('save-answers', $csfrToken)) {
                throw new Exception('Invalid request');
            }

            if ($validate->isValid()) {
                return $this->redirect(" /find-a-commercial-agreement/start-journey/{$journeyId}?q={$searchBy}");
            }

            //if it's not redirected it means that we have an error
            $showError = 1;

        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchGetJourneysApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $journeysModel = new JourneysModel($api,$searchBy);
        $journeys = $journeysModel->getJourneys();
        
        if(count($journeys) === 1){

            $redirect = $request->query->get('r');
            if(!empty($redirect)){
                return $this->redirect("/find-a-commercial-agreement/landing-page?q={$searchBy}");

            }
            $gmLiteOldAnswer = $journeyId;
            $journeyId = $journeys[0]["journeyId"];
            return $this->redirect(" /find-a-commercial-agreement/start-journey/{$journeyId}?q={$searchBy}&old={$gmLiteOldAnswer}");
        }

        $journeys = $this->orderingJourneys($journeys);

        $renderData = [
            'searchBy' => rawurldecode($searchBy),
            'journeys' => $journeys,
            'journeyId' => !empty($journeyId) ? $journeyId : null,
            'pageTitle' => $this->pageTitle,
            'errorsMessages'=> $this->errorMessage
        ];

        if(!empty($showError)){
            $renderData['showError'] = 1;
            $renderData['errorMessage'] = $this->errorMessage[0]['errorMessage'];
        }

        return $this->render('pages/guide_match_journeys.html.twig', $renderData );
    }

    private function validateUserAnswer(string $formType, array $userAnswer)
    {
        return ValidatorsFactory::getValidator($formType, $userAnswer);
    }

    private function orderingJourneys(array $journeys)
    {
        $indexCount = 0;
        foreach ($journeys as $journey) {
            if ($journey['modifier'] == 'GM Lite'){
                $temp = $journeys[$indexCount];
                unset($journeys[$indexCount]);
                array_push($journeys, $temp);
                break;
            }
            $indexCount += 1;
        }
        return $journeys;
    }
}