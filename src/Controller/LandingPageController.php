<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use \Exception;

class LandingPageController extends AbstractController
{
    public function landingPage(Request $request)
    {
        $q = $request->query->get('q');
      
       
        if ($q == 'linen') {
            $journeyId = 'b87a0636-654e-11ea-bc55-0242ac130003';
        } elseif ($q == 'legal') {
            $journeyId = 'ccb5c730-75b5-11ea-bc55-0242ac130003';
        } elseif ($q == 'legal_tech') {
            $journeyId = 'ccb6174e-75b5-11ea-bc55-0242ac130003';
        //   $q= 'legal';
        } else {
            die('We have mockups API just for linen and legal.');
        }

        if (empty($q)) {
            throw new Exception('You need to provide a word for Guide Match Journey');
        }

        return $this->render('pages/landing_page.html.twig', [
            'journeyUuid' => $journeyId,
            'searchBy' => $q,
            'pageTitle'=>'Langing Page'
        ]);
    }
}
