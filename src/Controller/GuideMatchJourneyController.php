<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;

class GuideMatchJourneyController extends AbstractController
{
    public function journey($journeyUuid, $questionUuid)
    {
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));
        $model = new GuideMatchJourneyModel($api, $journeyUuid, $questionUuid);
        
        return $this->render('pages/guide_match_questions.html.twig', [
            'journeyUuid' => $journeyUuid,
            'definedAnswers' => $model->getDefinedAnswers(),
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->gettype(),
            'hint' => $model->getHint(),
        ]);
    }
}
