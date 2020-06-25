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
        echo file_get_contents('https://api.ipify.org?format=json');
        $q = $request->query->get('q');
        if (!empty($q)) {
            $url = ($q == 1) ? 'https://webdev-cms.crowncommercial.gov.uk/wp-json': 'https://webdev-cms.crowncommercial.gov.uk';

            $api = new Wordpress($url);
            $id = 22;
            $menu = $api->getMenu($id);
            dd($menu);
        }

        return new Response('AT LEAST IT SHOULD RETURN THIS CONTROLLER ');
    }

    public function curl(Request $request)
    {
        echo file_get_contents('https://api.ipify.org?format=json');
        $q = $request->query->get('q');
        if (!empty($q)) {
            $client = HttpClient::create();
            $url = ($q == 1) ? 'https://webdev-cms.crowncommercial.gov.uk/wp-json/wp-api-menus/v2/menus/22': 'https://webdev-cms.crowncommercial.gov.uk/wp-json';
            $response = $client->request('GET', $url);
            try {
                $content = $response->getHeaders();
                $status = $response->getStatusCode();
                dd($content, $status);
            } catch (\Exception $e) {
                throw new \Exception('Invalid API response:'.$e->getMessage());
            }
        }
        return new Response('AT LEAST IT SHOULD RETURN THIS CONTROLLER ');
    }

    public function testApiUrl()
    {
        echo getenv('APP_API_BASE_URL');die;
    }
}
