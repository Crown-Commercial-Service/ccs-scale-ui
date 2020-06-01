<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    public function index(Request $request)
    {
        echo 'Test: 1';
        echo '<br/>';
        $debug = $request->query->get('debug');
        $test  = $request->query->get('test');
        if (!empty($debug)) {
            dump($_SERVER);
            die();
        }
     

        return new Response('Uri variable test : ' . $test);
    }
}
