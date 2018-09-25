<?php

/**
 * Expressive routed middleware
 */

/**
 * @var \Zend\Expressive\Application $app
 *
 * Define a GET route.
 * Moved from the front controller, index.php
 */
$app->route('/', \Movies\Middleware\RenderMoviesMiddleware::class, ['GET']);