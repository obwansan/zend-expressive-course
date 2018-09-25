<?php

use Zend\Expressive\Application;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Create a new DI container.
$container = include 'config/container.php';

/**
 * Register a service with the container.
 * Maps the name of the class to the factory that creates it.
 */
//$container->setFactory(
//    RenderMoviesMiddleware::class,
//    RenderMoviesMiddlewareFactory::class
//);

$container->setFactory('MovieData', function() {
    return include 'data/movies.php';
});

/**
 * Instantiate a new Application object by using AppFactory’s
 * static create method. Application objects are the core of a Zend
 * Expressive application. They contain, at their most basic, a router,
 * and a DI container.
 */
//$app = AppFactory::create($container);

/** @var Application $app */
$app = $container->get(Application::class);

/**
 * Respectively, these ensure the routes exist in the routing table, and have
 * a handler assigned to them, so they can be dispatched when they’re requested.
 */
$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();

require 'config/pipeline.php';
require 'config/routes.php';

$app->run();
