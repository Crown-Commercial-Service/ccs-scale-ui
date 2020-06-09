<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    public function home()
    {
        return $this->redirectToRoute('landing_page', ['q' => 'legal']);
    }
}
