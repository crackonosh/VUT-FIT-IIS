<?php

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

return function (Request $request, Throwable $e, bool $displayErrorDetails) {
    if ($request->getMethod() == 'OPTIONS')
    {
        $context = RouteContext::fromRequest($request);
        $routingResults = $context->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        return (new Response())
            ->withHeader('Access-Control-Allow-Methods', implode(',', $methods) . ', OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', $requestHeaders)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
    
    $response = new Response(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED);
    return $response;
};