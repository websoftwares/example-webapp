<?php
/*
|--------------------------------------------------------------------------
| Error reporting enabled default remove for production
|--------------------------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*
|--------------------------------------------------------------------------
| Autoload classes
|--------------------------------------------------------------------------
*/
include "vendor/autoload.php";
/*
|--------------------------------------------------------------------------
| Config IoC container object.
|--------------------------------------------------------------------------
*/
$container = new League\Container\Container();
/*
|--------------------------------------------------------------------------
| Config Request <== Maybe swap out for PSR-7
|--------------------------------------------------------------------------
*/
$container->add('request', function () {
    return Symfony\Component\HttpFoundation\Request::createFromGlobals();
});
/*
|--------------------------------------------------------------------------
| Config Response <== Maybe swap out for PSR-7
|--------------------------------------------------------------------------
*/
$container->add('response', function () {
    return new Symfony\Component\HttpFoundation\Response();
});
/*
|--------------------------------------------------------------------------
| Config Router
|--------------------------------------------------------------------------
*/
$container->add('router', function () {
    $router_factory = new \Aura\Router\RouterFactory();
    $router = $router_factory->newInstance();
    return $router;
});
/*
|--------------------------------------------------------------------------
| Dispatch
| A good Idea would be an introduction of a middleware handler
|--------------------------------------------------------------------------
*/
try {
    $dispatcher = new Websoftwares\Skeleton\Dispatcher;
    $dispatcher($container);
} catch (\Exception $e) {
    // This is to be moved to a package NotFound
    $response  = $container->get('response');
    $response->setContent($e->getMessage());
    $response->headers->set('Content-Type', 'text/plain');
    $response->setStatusCode($response::HTTP_NOT_FOUND);
    $response->send();
}