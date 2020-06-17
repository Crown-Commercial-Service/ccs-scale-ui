<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Studio24\Frontend\Cms\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class TestWordpressController extends AbstractController
{

    public function index(Request $request)
    {
        $q = $request->query->get('q');
        if (!empty($q)) {
            $api = new Wordpress('https://webdev-cms.crowncommercial.gov.uk/wp-json/');
            $id = 22;
            $menu = $api->getMenu($id);
            return $this->json($menu);
        }

        return new Response('AT LEAST IT SHOULD RETURN THIS CONTROLLER ');

    }
}
