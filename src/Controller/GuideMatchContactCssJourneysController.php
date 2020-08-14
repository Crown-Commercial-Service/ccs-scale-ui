<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Models\UserAnswers;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;

class GuideMatchContactCssJourneysController extends AbstractController
{
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
            'pageTitle' => 'Contact CCS',
            'historyAnswered' => "",
            'redirectToResultPage' => false,
            'agreements' => "",
            'journeysPage'=>1
        ]);
        
    }
}
