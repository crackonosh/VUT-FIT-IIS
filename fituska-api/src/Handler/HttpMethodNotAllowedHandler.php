<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

return function (Request $request, Throwable $e, bool $displayErrorDetails) {
    if ($request->getMethod() == 'OPTIONS')
    {
        return new Response();
    }
    
    throw $e;
};