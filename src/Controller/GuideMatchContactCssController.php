<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\GuideMatchJourneyModel;
use App\GuideMatchApi\GuideMatchJourneyApi;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;

class GuideMatchContactCssController extends AbstractController
{
    public function contactCss(Request $request, $journeyId, $journeyInstanceId,$questionUuid, $journeyHistory, $gPage)
    {

        return $this->render('pages/contact_css.html.twig', [
           
        ]);
        
    }
}
