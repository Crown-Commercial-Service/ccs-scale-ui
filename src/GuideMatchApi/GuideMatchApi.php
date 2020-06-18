<?php 
 
 declare(strict_types=1);

 namespace App\GuideMatchApi;
 
 use Symfony\Component\HttpClient\CurlHttpClient;

 class GuideMatchApi{

    protected $httpClient;
    protected $baseApiUrl;

    public function __construct(CurlHttpClient $httpClient, string $baseApiUrl)
    {
        $this->httpClient = $httpClient;
        $this->baseApiUrl = $baseApiUrl;
    }
 }

?>