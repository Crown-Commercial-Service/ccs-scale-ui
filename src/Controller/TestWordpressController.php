<?php

namespace App\Controller;

use Studio24\Frontend\Cms\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TestWordpressController extends AbstractController
{

    public function index()
    {
        $api = new Wordpress('https://webdev-cms.crowncommercial.gov.uk/wp-json/');
        $id = 22;
        $menu = $api->getMenu($id);
        dd($menu);
    }
}
