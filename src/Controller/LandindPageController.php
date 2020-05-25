<?php

declare(strict_types=1);

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchModel;


class LandingPageController extends AbstractController
{

    public function guideMatchJourney(Request $request)
    {
        $q =$request->query->get('q');
        $httpClient = HttpClient::create();
        $model = new GuideMatchModel($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_APId'), $q);

       
     }

}
?>