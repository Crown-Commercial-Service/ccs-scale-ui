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
      
        if (empty($q)) {
            throw new Exception('You need to provide a word for Guide Match Journey');
        }


        return $this->render('pages/landing_page.html.twig', [
            'searchBy' => $q,
            'pageTitle'=>'Landing Page'
        ]);
    }
}
