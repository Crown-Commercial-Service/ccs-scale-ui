<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GuideMatchContactCssJourneysController extends AbstractController
{
    private $pageTitle = 'Contact CCS';

    public function contactCss(Request $request)
    {

        $searchBy = $request->query->get('q');

       
        return $this->render('pages/contact_css.html.twig', [
           
            'searchBy' => $searchBy,
            'searchByEncoded' => rawurlencode($searchBy),
            'journeyId' => "",
            'journeyInstanceId' => "",
            'journeyHistory' => "",
            'lastPage' => 0,
            'lastQuestionId' => "",
            'pageTitle' => $this->pageTitle,
            'historyAnswered' => "",
            'redirectToResultPage' => false,
            'agreements' => "",
            'journeysPage'=>1
        ]);
        
    }
}
