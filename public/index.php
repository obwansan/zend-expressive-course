<?php

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\AppFactory;
use Zend\ServiceManager\ServiceManager;
use Movies\Middleware\RenderMoviesMiddleware;


/**
 * __DIR__ is the name of directory containing this file.
 * dirname() returns the directory name from a path.
 * chdir() changes PHP's current directory to directory passed to it.
 * So chdir(dirname(__DIR__)); changes the current working directory (CWD)
 * to be the directory containing this file.
 */
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Create a new DI container.
$container = new ServiceManager();

/**
 * Register a service with the container.
 * 'MovieData' is the name of the service.
 * The anonymous function provides the data for the service.
 * The service in the container will be in the format:
 * 'MovieData'  => 'data/movies.php'
 * So, a request for the service, such as $container->get('MovieData')
 * will return 'data/movies.php'
 */
$container->setFactory('MovieData', function() {
    return include 'data/movies.php';
});

/**
 * Instantiate a new Application object by using AppFactory’s
 * static create method. Application objects are the core of a Zend
 * Expressive application. They contain, at their most basic, a router,
 * and a DI container.
 */
$app = AppFactory::create($container);

/**
 * @var ServerRequestInterface $request
 * @var DelegateInterface $delegate
 *
 * Define a GET route.
 * New-up a RenderMoviesMiddleware class and pass its constructor
 * the movie data from the container.
 */

$app->get('/', (new RenderMoviesMiddleware($container->get('MovieData'))));

/**
 * Respectively, these ensure the routes exist in the routing table, and have
 * a handler assigned to them, so they can be dispatched when they’re requested.
 */
$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();

$app->run();
