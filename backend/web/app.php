<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$env = getenv('SYMFONY_ENV') ?: 'production';

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance.
// Change 'sf2' to a unique prefix in order to prevent cache key conflicts
// with other applications also using APC.
/*
*/

if ('production' === $env || 'staging' === $env) {
    $loader = new ApcClassLoader('sf2', $loader);
    $loader->register(true);
}

$debug = getenv('SYMFONY_DEBUG') !== '0' && $env !== 'production';

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();
//require_once __DIR__.'/../app/AppCache.php';
//$kernel = new AppCache($kernel);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
