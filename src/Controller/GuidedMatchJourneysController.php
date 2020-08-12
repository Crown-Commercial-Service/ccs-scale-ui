<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use App\GuideMatchApi\GuideMatchGetJourneysApi;
use App\Models\JourneysModel;
use Exception;



class GuidedMatchJourneysController extends AbstractController{


    public function searchJourneys(Request $request){

        $searchBy = $request->query->get('q');
        if(empty($searchBy)){
            throw new Exception('Invalid request');
            
        }


        if($request->getMethod() === "POST"){

            $csfrToken = $request->request->get('token');
            $journeyId = $request->request->get('uuid');
            $searchBy = $request->request->get('searchBy');

            if( empty($journeyId) && !$this->isCsrfTokenValid('save-answers', $csfrToken)) {
                throw new Exception('Invalid request');
            }

            return $this->redirect(" /find-a-commercial-agreement/start-journey/{$journeyId}?q={$searchBy}");

        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchGetJourneysApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

        $journeysModel = new JourneysModel($api,$searchBy);
        $journeys = $journeysModel->getJourneys();
        if(count($journeys) === 1){
            $journeyId = $journeys[0]["journeyId"];
            return $this->redirect(" /find-a-commercial-agreement/start-journey/{$journeyId}?q={$searchBy}");
        }

        return $this->render('pages/guide_match_journeys.html.twig', [
            'searchBy' => rawurldecode($searchBy),
            'journeys' => $journeys,
            'pageTitle' => 'Select a Journey',
        ]);
    }

}