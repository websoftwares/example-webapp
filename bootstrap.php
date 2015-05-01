<?php
session_start();
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
include 'vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Load environment settings
|--------------------------------------------------------------------------
*/
Dotenv::load(__DIR__);
/*
|--------------------------------------------------------------------------
| Config IoC container object.
|--------------------------------------------------------------------------
*/
$container = new League\Container\Container();
/*
|--------------------------------------------------------------------------
| Config Request PSR-7
|--------------------------------------------------------------------------
*/
$container->add('request', function () {
    return \Phly\Http\ServerRequestFactory::fromGlobals();
});
/*
|--------------------------------------------------------------------------
| Config Response PSR-7
|--------------------------------------------------------------------------
*/
$container->add('response', function () {
    return new \Phly\Http\Response();
});
/*
|--------------------------------------------------------------------------
| Config Middleware PSR-7
|--------------------------------------------------------------------------
*/
$container->add('middleware', function () {
    return new \Websoftwares\Middleware\MiddlewareRunner;
});
/*
|--------------------------------------------------------------------------
| Config Router
|--------------------------------------------------------------------------
*/
$container->add('router', function () {

    $routerContainer = new \Aura\Router\RouterContainer;
    return $routerContainer;
});
/*
|--------------------------------------------------------------------------
| Dispatch
| A good Idea would be an introduction of a middleware handler
|--------------------------------------------------------------------------
*/
try {
    $dispatcher = new Websoftwares\Skeleton\Dispatcher();
    $server = $dispatcher($container);
    
} catch (\Exception $e) {

    // This is to be moved to a package NotFound
    $callable = function($request, $response) use($e) {

        $response = $response->withStatus(404);
        $response->getBody()->write($e->getMessage());

        return $response;
    };

    $server = new \Phly\Http\Server($callable,$container->get('request'),$container->get('response'));
}
// Listen
$server->listen();