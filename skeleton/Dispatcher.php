<?php

namespace Websoftwares\Skeleton;

use League\Container\Container;

/**
 * @author Boris <boris@websoftwar.es>
 */
class Dispatcher
{
    /**
     * getRoutes Return routes.
     *
     * @return array
     */
    public function getRoutes($path)
    {
        $file = '../config/routes.php';
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
        $response = $container->get('response');
        $middleware = $container->get('middleware');
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Try getting routes
        try {
            $routes = $this->getRoutes($path);
        } catch (\OutOfRangeException $e) {
            // TODO log here ?
            throw $e;
        }

        // retrieve router from container
        $routerContainer = $container->get('router');
        $map = $routerContainer->getMap();

        // Loop over routes
        if ($routes) {
            // Loop over configured routes
            foreach ($routes as $key => $routeConfiguration) {
                $routeConfiguration($map);
            }
        }

        // Match with Psr\Http\Message\ServerRequestInterface $request
        $matcher = $routerContainer->getMatcher();
        $route = $matcher->match($request);

        // If we have valid route
        if ($route) {

            // Register Service Provider for package
            // More generic providers to be used across the system
            // can be registerd after the container instance is created
            if (isset($route->extras['provider']) && $route->extras['provider']) {
                $container->addServiceProvider($route->extras['provider']);
            }

            // If we have attributes transfer its attributes to the $request
            if ($route->attributes) {
                // Transfer
                foreach ($route->attributes as $key => $val) {
                    $request = $request->withAttribute($key, $val);
                }
            }

            // Get the action based on route name (key)
            $callable = $container->get($route->name); // <== This is where the magic happens ,all objects and their depdendencies are here forged

            // Add all available middleware to run
            if (isset($route->extras['middleware']) && $route->extras['middleware']) {
                foreach ($route->extras['middleware'] as $key => $m) {
                    $middleware->add($m);
                }

                // The middleware becomes the callable
                $middleware->add($callable);
                $callable =  $middleware;
            }

            return $server = new \Phly\Http\Server($callable, $request, $response);

        // Throw exception
        } else {
            throw new \Exception('No resource found');
        }
    }
}
