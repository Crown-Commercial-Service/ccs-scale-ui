<?php

declare(strict_types=1);

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchModel;


class LandingPageController extends AbstractController
{

    public function landingPage(Request $request)
    {
        $q =$request->query->get('q');
        $httpClient = HttpClient::create();
        $model = new GuideMatchModel($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'), $q);
       
        $this->render('pages/landing_page.html.twig', [
            'journeyUuid' => $model->getJourneyUuid(),
            'questionUuid' => $model->getJourneyQuestionUuid(),
        ]);
     }
}
?>