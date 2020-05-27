<?php

namespace App\Controller;

use Psr\SimpleCache\CacheInterface;
use Studio24\Frontend\Cms\Wordpress;
use Studio24\Frontend\ContentModel\ContentModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GuidedMatchPagesController extends AbstractController
{
    /**
     * Events Rest API data
     *
     * @var Wordpress
     */
    protected $api;

    public function __construct(CacheInterface $cache)
    {
        $this->api = new Wordpress(
            getenv('APP_API_BASE_URL'),
            new ContentModel(__DIR__ . '/../../config/content/content-model.yaml')
        );
        $this->api->setContentType('events');
        $this->api->setCache($cache);
        $this->api->setCacheLifetime(900);
    }


    public function questionsPage(string $slug)
    {
        return $this->render('pages/questions_page.html.twig', [
            'slug' => $slug
        ]);
    }
}
