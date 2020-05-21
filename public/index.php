<?php
$ddebug = false;

if(!empty($_GET['debug']))
{
    $ddebug = $_GET['debug'];
}

if($ddebug == 1){

    die('Entered inn framework');
}

use App\CacheKernel;
use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
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


// if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
//     Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
// }

// if($ddebug == 4 ){

//     die('Before TRUSTED_HOSTS after TRUSTED_PROXIES' );
// }


// if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
//     Request::setTrustedHosts([$trustedHosts]);
// }


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

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

if($ddebug == 7 ){

    die('After handle request');
}

$response->send();

if($ddebug == 8 ){

    die('After handle send');
}
$kernel->terminate($request, $response);
