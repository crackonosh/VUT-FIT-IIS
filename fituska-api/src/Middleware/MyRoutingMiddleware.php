<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Middleware\RoutingMiddleware;
use Slim\Psr7\Response;
use Slim\Routing\RoutingResults;
use Psr\Http\Message\ResponseFactoryInterface;

class MyRoutingMiddleware extends RoutingMiddleware
{

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->performRouting($request);
        if ($this->isPreflight($request)) {
            return new Response(200);
        }
        return $handler->handle($request);
    }

    protected function isPreflight(ServerRequestInterface $request): bool
    {
        return $request->getMethod() === "OPTIONS" &&
            $request->getHeaderLine("Access-Control-Request-Method") !== '';
    }

    protected function resolveRoutingResultsFromRequest(ServerRequestInterface $request): RoutingResults
    {
        $accessControlRequestMethod = $request->getHeaderLine("Access-Control-Request-Method");
        $isPreflight = $this->isPreflight($request);
        return $this->routeResolver->computeRoutingResults(
            $request->getUri()->getPath(),
            $isPreflight ? $accessControlRequestMethod : $request->getMethod()
        );
    }
}