<?php
/**
 * Temporary controller used just for demo
 */
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TacticalSerchResultController extends AbstractController
{
    public function backToSearch(Request $request)
    {
        $q = $request->query->get('q');
      
        return $this->render('pages/tactical_search_result.html.twig', [
            'searchBy' => $q
        ]);
    }
}
