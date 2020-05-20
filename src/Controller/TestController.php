<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
 
 class TestController extends  AbstractController{

    public function index(){
        echo 'test';
dump(getenv());
dump(getenv('GUIDE_MATCH_DECISION_TREE_API'));

die();
        return new Response('Test Redirect');
    }

 }
