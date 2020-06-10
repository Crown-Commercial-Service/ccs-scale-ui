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
    public function journeyResult(Request $request, $journeyId, $journeyInstanceId, $journeyData)
    {
        die('x');
               

        return $this->render('pages/result_page.twig', [
           
        ]);
    }
}
