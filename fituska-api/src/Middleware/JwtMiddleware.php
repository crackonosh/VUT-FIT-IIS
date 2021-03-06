<?php

use App\Services\AuthService;
use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (Request $request, RequestHandler $handler) use ($app): Response
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

    //$key = $app->getContainer()->get('settings')['jwt-key'];
    $as = new AuthService();
    try {
        $jwt = $as->decodeJWT($token);
    }
    catch (Exception $e)
    {
        $response = new Response(StatusCodeInterface::STATUS_FORBIDDEN);

        $response->getBody()->write(json_encode(
            array("JWT decoding error: {$e->getMessage()}")
        ));

        return $response
            ->withHeader('Content-type', 'application/json');
    }

    $request = $request->withAttribute('jwt', $jwt);
    return $handler->handle($request);
};