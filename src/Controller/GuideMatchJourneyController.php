<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;

class GuideMatchJourneyController extends AbstractController
{
    public function journey(Request $request,$journeyUuid, $questionUuid)
    {
        $httpClient = HttpClient::create();
        $api = new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));

        $response = [];

        if($request->isMethod('post')){
            if (!empty($request->request->get('uuid'))) {
                $response = !is_array($request->request->get('uuid')) ? [$request->request->get('uuid')] : $request->request->get('uuid');
            }else{
                $this->redirect($request->server->get('HTTP_REFERER'));
            }
        }

        $model = new GuideMatchJourneyModel($api, $journeyUuid, $questionUuid, $response);

        return $this->render('pages/guide_match_questions.html.twig', [
            'journeyUuid' => $journeyUuid,
            'definedAnswers' => $model->getDefinedAnswers(),
            'uuid' => $model->getUuid(),
            'text' => $model->getText(),
            'type' => $model->getType(),
            'hint' => $model->getHint(),
        ]);
    }
}
