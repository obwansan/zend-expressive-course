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
 * Maps the name of the class to the factory that creates it.
 */
$container->setFactory(
    RenderMoviesMiddleware::class,
    RenderMoviesMiddlewareFactory::class
);

/**
 * Instantiate a new Application object by using AppFactory’s
 * static create method. Application objects are the core of a Zend
 * Expressive application. They contain, at their most basic, a router,
 * and a DI container.
 */
$app = AppFactory::create($container);

/**
 * Define a GET route.
 * when the default route '/' is requested, $app attempts to retrieve
 * an instance of RenderMoviesMiddleware from the DI container. It sees
 * that RenderMoviesMiddleware maps to RenderMoviesMiddlewareFactory,
 * which returns the fully instantiated RenderMoviesMiddleware object, 
 * containing the movie data.
 */
$app->get('/', RenderMoviesMiddleware::class);

/**
 * Respectively, these ensure the routes exist in the routing table, and have
 * a handler assigned to them, so they can be dispatched when they’re requested.
 */
$app->pipeRoutingMiddleware();
$app->pipeDispatchMiddleware();

$app->run();
