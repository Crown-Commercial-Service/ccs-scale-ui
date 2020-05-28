<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use \Exception;

class LandingPageController extends AbstractController
{
    public function landingPage(Request $request)
    {
        $q = $request->query->get('q');

        if (empty($q)) {
            throw new Exception('You need to provide a word for Guide Match Journey');
        }

        $httpClient = HttpClient::create();
        $api =  new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));
        $model = new GuideMatchModel($api, $q);
       
        return $this->render('pages/landing_page.html.twig', [
            'journeyUuid' => $model->getJourneyUuid(),
            'questionUuid' => $model->getJourneyQuestionUuid(),
            'searchBy' => $q,
        ]);
    }
}
