<?php

declare(strict_types=1);

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Exception;

class SpeedRaportController extends AbstractController
{
    public function downloadLog()
    {
        $response = new Response();

        $response->headers->set('Content-Type','text/plain');
        $response = new BinaryFileResponse ( '../public/speedTest.log' );

       return $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'speed.log');


    }

    public function clearReport(){

        $file = '../public/speedTest.log';

        $f = @fopen($file, "r+");
        if ($f !== false) {
            ftruncate($f, 0);
            fclose($f);
        }
       

      
        die('x');
    


    }
}
