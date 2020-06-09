<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;

class GuideMatchJourneyResultController extends AbstractController
{
    public function journey(Request $request, $journeyId, $journeyHistory)
    {
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));

  
        $model = new GuideMatchJourneyModel($api);
               

        return $this->render('pages/guide_match_questions.html.twig', [
           
        ]);
    }
}
