<?php

namespace Movies\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Movies\BasicRenderer;
use Psr\Http\Message\{ResponseInterface,ServerRequestInterface};
use Zend\Diactoros\Response\HtmlResponse;

/**
 * This class just refactors the closure (i.e. anonymous function handler)
 * in index.php into a class. The __invoke method still takes the same
 * parameters, contains the same body, and returns the same response.
 *
 * By refactoring it out into a separate class, we’re able to create a more
 * reusable and maintainable handler.
 *
 * Class RenderMoviesMiddleware
 * @package Movies\Middleware
 */
class RenderMoviesMiddleware
{
    /**
     * @var array|\Traversable
     */
    private $movieData;

    /**
     * RenderMoviesMiddleware constructor.
     * @param array|\Traversable $movieData
     */
    public function __construct($movieData)
    {
        $this->movieData = $movieData;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @internal param ResponseInterface $response
     * @internal param $next
     */
    public function __invoke(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $renderer = (new BasicRenderer())(
            $this->movieData
        );
        return new HtmlResponse($renderer);
    }
}