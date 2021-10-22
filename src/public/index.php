<?php

use App\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

// Set container to create App with on AppFactory
AppFactory::setContainer(require __DIR__ . '/../../bootstrap.php');
$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, $args) {
    $myService = $this->get("settings")['doctrine']['metadata_dirs'];
    var_dump($myService);die();

    $response->getBody()->write(
        "kek"
    );
    return $response;
});

$app->get('/user', UserController::class . ':addUser');

$app->run();
