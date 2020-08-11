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
        $resultPage = $request->query->get('resultPage');
        $agreements = $request->query->get('agreements');

        $userAnswersFormatedForView = [];
        if ($journeyHistory != '0') {
            $decrypt = new Decrypt(urldecode($journeyHistory));
            $historyUserAnswers = json_decode($decrypt->getDecryptedString(), true);
            $userAnswers = new UserAnswers();
            $userAnswersFormatedForView = $userAnswers->formatForView($historyUserAnswers, $questionUuid);
        }

        return $this->render('pages/contact_css.html.twig', [
           
            'searchBy' => $searchBy,
            'searchByEncoded' => rawurlencode($searchBy),
            'journeyId' => $journeyId,
            'journeyInstanceId' => $journeyInstanceId,
            'journeyHistory' => $journeyHistory,
            'lastPage' => $gPage,
            'lastQuestionId' => $questionUuid,
            'pageTitle' => 'Contact CCS',
            'historyAnswered' => $userAnswersFormatedForView,
            'redirectToResultPage' => $resultPage,
            'agreements' => !empty($agreements) ? urlencode($agreements) : ''
        ]);
        
    }
}
