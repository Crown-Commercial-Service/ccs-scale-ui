<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Exception;

class StartJourneyController extends AbstractController
{
    public function startJourney(Request $request, $journeyUuid)
    {
        $searchBy = $request->query->get('q');


        if ($request->getMethod() === 'POST') {
            $csfrToken = $request->request->get('token');
            if (!$this->isCsrfTokenValid('save-answers', $csfrToken)) {
                throw new Exception('Invalid request');
            }
        }

        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDED_MATCH_SERVICE_ROOT_URL'));
       
        $model = new GuideMatchJourneyModel($api);
        $model->startJourney($journeyUuid, $searchBy);
        $questionText = $model->getText();
        
        return $this->render('pages/guide_match_questions.html.twig', [
            'journeyInstanceId' => $model->getJourneyInstanceId(),
            'journeyId' => $journeyUuid,
            'definedAnswers' => $model->getDefinedAnswers(),
            'journeyHistory' => '0',
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
            'searchBy' => $searchBy,
            'userAnswers' => [],
            'gPage' => 1,
            'lastPage' => 0,
            'pageTitle' => $questionText,
            'currentPage'=>1
        ]);
    }
}
