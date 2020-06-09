<?php

namespace App\Controller;

class ResultPageController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function resultPage()
    {
        return $this->render('pages/result_page.html.twig');
    }
}
