<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Models\UserAnswers;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Decrypt;

class GuideMatchContactCssController extends AbstractController
{
    public function contactCss(Request $request, $journeyId, $journeyInstanceId,$questionUuid, $journeyHistory, $gPage)
    {

        $searchBy = $request->query->get('q');
        $userAnswersFormatedForView = [];
        if ($journeyHistory != '0') {
            $decrypt = new Decrypt(urldecode($journeyHistory));
            $historyUserAnswers = json_decode($decrypt->getDecryptedString(), true);
            $userAnswers = new UserAnswers();
            $userAnswersFormatedForView = $userAnswers->formatForView($historyUserAnswers, false);
        }
        return $this->render('pages/contact_css.html.twig', [
           
            'searchBy' => $searchBy,
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'lastPage' => $gPage,
            'lastQuestionId' => $questionUuid,
            'pageTitle' => 'Contact CCS',
            'historyAnswered' => $userAnswersFormatedForView
        ]);
        
    }
}
