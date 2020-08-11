<?php

declare(strict_types=1);

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GuidedMatchHealthCheckController extends AbstractController{


    public function testHealth(){
       return new Response('Ok', Response::HTTP_OK);
    }
}