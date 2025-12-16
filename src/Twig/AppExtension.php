<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('modify_url', [$this, 'modifyUrl']),
        ];
    }

    /**
     * This is a Twig filter that looks for every url from the header and footer and if one has bad domain it replaces it with a good one,
     * also it adds the live domain for the relative paths
     */
    public function modifyUrl(string $cmsUrl) :string
    {
        $url = '';
        $badDomains  = [
            'webdev-cms.crowncommercial.gov.uk',
            'webprod-cms.crowncommercial.gov.uk',
            'ccs-agreements.cabinetoffice.localhost',
            'webuat-cms.crowncommercial.gov.uk',
        ];

        foreach ($badDomains as $badDomain) {
            if ($badDomain == parse_url($cmsUrl, PHP_URL_HOST)) {
                $url = str_replace($badDomain, parse_url($_ENV['CCS_DOMAIN'], PHP_URL_HOST), $cmsUrl);
            }
        }

        if ($cmsUrl[0] == '/') {
            $url = $_ENV['CCS_DOMAIN'] . $cmsUrl;
        }

        return $url ? $url : $cmsUrl;
    }
}
