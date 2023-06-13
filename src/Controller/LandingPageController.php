<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use \Exception;

class LandingPageController extends AbstractController
{
    public function landingPage(Request $request)
    {
        $q = $request->query->get('q');
      
        if (empty($q)) {
            throw new Exception('You need to provide a word for Guide Match Journey');
        }


        return $this->render('pages/landing_page.html.twig', [
            'searchBy' => $q,
            'pageTitle'=>'Landing Page'
        ]);
    }

    /**
     * Simple healthcheck
     *
     * @return JsonResponse
     */
    public function healthcheck()
    {
        $required = '7.1.3';
        if (version_compare(PHP_VERSION, $required) < 0) {
            return new JsonResponse(['message' => sprintf("PHP version must be %s or above, found '%s'", $required, PHP_VERSION)], 500);
        }

        // Check required environment variables
        $required = [
            'GUIDED_MATCH_SERVICE_ROOT_URL',
            'GUIDED_MATCH_SERVICE_API_KEY',
            'AGREEMENTS_SERVICE_ROOT_URL',
            'AGREEMENTS_SERVICE_API_KEY',
            'CCS_DOMAIN',
            'WEBCMS_ROOT_URL',
        ];
        foreach ($required as $variable) {
            if (empty(getenv($variable))) {
                return new JsonResponse(['message' => sprintf("Environment variable '%s' not set", $variable)], 500);
            }
        }

        return new JsonResponse(['message' => 'OK']);
    }
}
