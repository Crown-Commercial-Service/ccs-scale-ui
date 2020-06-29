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
        if ($string[0] == '/') {
            return "https://www.crowncommercial.gov.uk" . $string;
        }
        return $string;
    }   
}