<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;
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
            $url = ($q == 1) ? 'https://webdev-cms.crowncommercial.gov.uk/wp-json': 'https://webdev-cms.crowncommercial.gov.uk';

            $api = new Wordpress($url);
            $id = 22;
            $menu = $api->getMenu($id);
            return $this->json($menu);
        }

        return new Response('AT LEAST IT SHOULD RETURN THIS CONTROLLER ');

    }

    public function curl(Request $request)
    {
        $q = $request->query->get('q');
        if (!empty($q)) {
            $client = HttpClient::create();
            $url = ($q == 1) ? 'https://webdev-cms.crowncommercial.gov.uk': 'https://webdev-cms.crowncommercial.gov.uk/wp-json/';
            $response = $client->request('GET', $url);
            $this->json($response);
        }


        return new Response('AT LEAST IT SHOULD RETURN THIS CONTROLLER ');

    }
}
