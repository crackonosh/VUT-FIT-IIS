<?php

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return array(
    "string" => function (Request $request, RequestHandler $handler): Response
    {
        $contentyType = $request->getHeaderLine('Content-Type');

        if (strstr($contentyType, 'application/json'))
        {
            $contents = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() == JSON_ERROR_NONE)
                $request = $request->withParsedBody($contents);
        }

        return $handler->handle($request);
    }
);