<?php

use App\CacheKernel;
use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
require dirname(__DIR__) . '/config/bootstrap.php';
// Set environment, default to prod
$env = $_SERVER['APP_ENV'] ?? 'prod';

$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));
$dbg = !empty($_GET['dbg']) ? $_GET['dbg'] : null;


if ($debug) {
    umask(0000);
    Debug::enable();
}

if($dbg == 1){

    dd($_SERVER);
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

if($dbg == 2){

    dd($_SERVER);
}

$kernel = new Kernel($env, $debug);

// Enable HTTP Cache for prod
// @see https://symfony.com/doc/current/http_cache.html
if ('prod' === $env) {
    $kernel = new CacheKernel($kernel);
}

if($dbg == 3){

    dd($_SERVER);
}
$uri = $_SERVER['REQUEST_URI'];

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

if($dbg == 4){

    dd($_SERVER);
}
$response->send();

if($dbg == 5){

    dd($_SERVER);
}

$kernel->terminate($request, $response);

