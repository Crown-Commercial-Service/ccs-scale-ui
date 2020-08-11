<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\GuideMatchApi\GuideMatchGetJourneysApi;
use App\Models\JourneysModel;

use \Exception;

class LandingPageController extends AbstractController
{
    public function landingPage(Request $request, $journeyId)
    {
        $q = $request->query->get('q');
      
        if (empty($q)) {
            throw new Exception('You need to provide a word for Guide Match Journey');
        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchGetJourneysApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));


        $journeysModel = new JourneysModel($api,$q);
        $nrOfJourneys = $journeysModel->getNumberOfJourneys();
        

        return $this->render('pages/landing_page.html.twig', [
            'journeyUuid' => $journeyId,
            'searchBy' => $q,
            'nrOfJourneys'=>$nrOfJourneys,
            'pageTitle'=>'Landing Page'
        ]);
    }
}
