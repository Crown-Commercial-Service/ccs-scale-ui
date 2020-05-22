<?php
$ddebug = false;

if(!empty($_GET['debug']))
{
    $ddebug = $_GET['debug'];
}

if($ddebug == 1){

    die('Entered inn framework');
    
}

if($ddebug == 10){

   $_SERVER['HTTP_HOST'] = 'sgs5c4khwd.execute-api.eu-west-2.amazonaws.com/dev/scale/buyer';
   $_SERVER['SERVER_NAME'] = 'sgs5c4khwd.execute-api.eu-west-2.amazonaws.com/dev/scale/buyer';
    
}

if($ddebug == 11){

    $_SERVER['HTTP_HOST'] = 'sgs5c4khwd.execute-api.eu-west-2.amazonaws.com';
    $_SERVER['SERVER_NAME'] = 'sgs5c4khwd.execute-api.eu-west-2.amazonaws.com';
     
 }

use App\CacheKernel;
use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use App\SymfonyExt\XRequest;


require dirname(__DIR__) . '/config/bootstrap.php';
// Set environment, default to prod
$env = $_SERVER['APP_ENV'] ?? 'prod';

if($ddebug == 2 ){
    dump($env);
    dump($_SERVER);
    die('Entered in framework');
}

$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
    umask(0000);
    Debug::enable();
}

if($ddebug == 3 ){
dump($ddebug);
    dump($_SERVER);
    die('Before TRUSTED_PROXIESzz');
}

Request::setTrustedProxies(
    // the IP address (or range) of your proxy
    // ['5.12.0.66', '130.176.33.140'],
    // trust *all* "X-Forwarded-*" headers
    
    //trust all requests
    ['127.0.0.1', 'REMOTE_ADDR'],
    Request::HEADER_X_FORWARDED_ALL,
);

/*
    if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
    }
*/

if($ddebug == 4 ){

    die('Before TRUSTED_HOSTS after TRUSTED_PROXIES' );
}


if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}


if($ddebug == 5 ){

    die('After TRUSTED_HOSTS');
}

$kernel = new Kernel($env, $debug);

// Enable HTTP Cache for prod
// @see https://symfony.com/doc/current/http_cache.html
if ('prod' === $env) {
  //  $kernel = new CacheKernel($kernel);
}

if($ddebug == 6 ){

    die('After HTTP Cache');
}

$xrequest = XRequest::createFromGlobals();


$request = Request::createFromGlobals();
$response = $kernel->handle($xrequest);

if($ddebug == 7 ){

    die('After handle request');
}

$response->send();

if($ddebug == 8 ){

    dump($request);
    die('After handle send');
}
$kernel->terminate($xrequest, $response);
