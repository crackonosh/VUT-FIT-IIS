<?php

use Domain\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

// Set container to create App with on AppFactory
AppFactory::setContainer(require __DIR__ . '/../../bootstrap.php');
$app = AppFactory::create();

/*
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $e, bool $displayErrorDetails) {
        $response = new Response();
        $response->getBody()->write('404 NOT FOUND');

        return $response->withStatus(404);
    }
);
*/


$app->get('/', function (Request $request, Response $response, $args) {
    $myService = $this->get("settings")['doctrine']['metadata_dirs'];
    var_dump($myService);die();

    $user = new User();
    var_dump($user);
    

    $response->getBody()->write(
        //json_encode($myService)
        "kek"
    );
    return $response;
});

$app->run();
