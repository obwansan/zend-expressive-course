<?php

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\AppFactory;
use Zend\ServiceManager\ServiceManager;


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
 * An alternate way of type hinting the parameters:
 *
 * @var ServerRequestInterface $request
 * @var DelegateInterface $delegate
 *
 * Define a GET route.
 * Pass the get method the route's path and handler (anonymous function).
 */
$app->get('/', function ($request, $delegate) use($container) {
    /**
     * The Middleware first calls an invokable, Movies\BasicRenderer.php,
     * which handles rendering the movie data (by retrieving the MovieData
     * service from the container) in tabular format. It stores the result
     * in a variable called $renderer.
     *
     * The syntax is a PHP 7 shorthand way of doing this:
     * $basicRenderer = new \Movies\BasicRenderer();
     * $movieData = $container->get('MovieData');
     * $renderer = $basicRenderer($movieData);
     *
     * Still not sure about this. If above is true the shorthand should be:
     * $renderer = new \Movies\BasicRenderer($container->get('MovieData'));
     * i.e. just pass the returned MovieData to the BasicRenderer() invokable.
     */
    $renderer = (new \Movies\BasicRenderer())(
        $container->get('MovieData')
    );
    return new HtmlResponse($renderer);
});

/**
 * Respectively, these ensure the routes exist in the routing table, and have
 * a handler assigned to them, so they can be dispatched when they’re requested.
 */
$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();

$app->run();
