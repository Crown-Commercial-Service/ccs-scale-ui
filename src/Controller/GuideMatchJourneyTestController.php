<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\GuideMatchApi\TestJourneyApi;

class GuideMatchJourneyTestController extends AbstractController
{
    public function guideMatchJourney(Request $request)
    {
        $q =$request->query->get('q');

        if (empty($q)) {
            echo 'Invalid query string: You should add a term for search' ;
            die();
        }

        dump($q);
        $test_model = new TestJourneyApi();
        $test_model->testJourney($q);
        die();
    }
}
