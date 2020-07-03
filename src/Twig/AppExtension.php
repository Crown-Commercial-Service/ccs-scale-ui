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

    public function modifyUrl($string)
    {
        // This is a temporaly fix untill links from wordpress are corectly rendered
        if (strpos($string, 'https://webdev-cms.crowncommercial.gov.uk')) {
            return str_replace('https://webdev-cms', 'https://www', $string);
        }

        if ($string[0] == '/') {
            return "https://www.crowncommercial.gov.uk" . $string;
        }
        
        return $string;
    }
}
