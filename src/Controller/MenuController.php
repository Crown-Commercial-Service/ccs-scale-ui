<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Psr16Cache;
use Strata\Frontend\Cms\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    /**
     * Frameworks Rest API data
     *
     * @var Wordpress
     */
    protected $api;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->api = new Wordpress(
            getenv('WEBCMS_ROOT_URL').'/wp-json/'
        );
        $psr16Cache = new Psr16Cache($cache);
        $this->api->setCache($psr16Cache);
        $this->api->setCacheLifetime(9000);
    }


    /**
     * Generic menu controller
     *
     * @param integer $id
     * @param string $templatePath
     * @param string $currentPath
     * @return Response
     */
    public function menu(int $id, string $templatePath = 'menus/default-menu.html.twig', bool $inlineMenu = false)
    {
        $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $menu = $this->api->getMenu($id);

        if (empty($menu)) {
            return new Response();
        }
        
       
        return $this->render($templatePath, [
            'menu' => $menu,
            'inlineMenu' => $inlineMenu
        ]);
    }
}
