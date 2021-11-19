<?php

use Slim\Psr7\Response;
use Slim\Psr7\Request;

return function (Request $request, Throwable $e, bool $displayErrorDetails){
    $response = new Response();
    $response->getBody()->write(json_encode(array(
        "Requested endpoint '{$request->getUri()->getPath()}' not found."
    )));

    return $response
        ->withHeader('Content-type', 'application/json')
        ->withStatus(404);
};