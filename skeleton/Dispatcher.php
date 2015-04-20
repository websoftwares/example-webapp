<?php

namespace Websoftwares\Skeleton;

use League\Container\Container;

/**
 * @author Boris <boris@websoftwar.es>
 */
class Dispatcher
{
    /**
     * getRoutes Return routes based on path info and convention names.
     *
     * @return array
     */
    public function getRoutes($path)
    {
        $path = explode("/" , $path);

        if (isset($path[1])) {
            $path = "/" . $path[1];
        } elseif(isset($path[0])) {
            $path = $path[0];
        }
        
        $file = '../routes'.$path.'/routes.php';
        if (! file_exists($file)) {
            throw new \OutOfRangeException('the file: '.$file.' could not be retrieved');
        }

        return include $file;
    }

    /**
     * __invoke.
     *
     * @param Container $container container with inversion of control dependencies
     *
     * @return
     */
    public function __invoke(Container $container)
    {
        $request = $container->get('request');
        $path = parse_url($request->server->get('REQUEST_URI'), PHP_URL_PATH);

        // Try getting routes
        try {
            $routes = $this->getRoutes($request->getPathInfo());
        } catch (\OutOfRangeException $e) {
            // TODO log here ?
            throw $e;
        }

        // retrieve router from container
        $router = $container->get('router');

        // Loop over routes
        if ($routes) {
            // Loop over configured routes
            foreach ($routes as $routeConfiguration) {
                $routeConfiguration($router);
            }
        }

        // Match route with SERVER
        $route = $router->match($path, $_SERVER);

        // If we have valid route
        if ($route) {

            // Register Service Provider for package
            // More generic providers to be used across the system can be registerd after the container instance is created
            if (isset($route->params['provider']) && $route->params['provider']) {
                $container->addServiceProvider($route->params['provider']);
            }

            // Get the action based on route name (key)
            $ActionClass = $container->get($route->name); // <== This is where the magic happens all objects and their depdendencies are here forged
            $ActionClass($route->params);

        // Throw exception
        } else {
            throw new \Exception('No resource found');
        }
    }
}
