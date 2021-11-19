<?php

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (Request $request, RequestHandler $handler): Response
{
    $authHeader = $request->getHeader('Authorization');
    if (empty($authHeader))
    {
        $response = new Response(StatusCodeInterface::STATUS_FORBIDDEN);

        $response->getBody()->write(json_encode(
            array("Unauthorized access to protected endpoint.")
        ));

        return $response
            ->withHeader('Content-type', 'application/json');
    }

    $token = explode(' ', $authHeader[0])[1];
    var_dump($token);
    $request = $request->withAttribute('jwt', $token);

    return $handler->handle($request);
};