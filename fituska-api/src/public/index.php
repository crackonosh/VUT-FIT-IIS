<?php

// leave this here if you don't wanna get mad about `uSe CoNtInUe 2 hur dur dur` warning msgs (might break something :pausechamp:) :loudasleeper:
//error_reporting(E_ALL ^ E_WARNING); 

use App\Controller\CourseController;
use App\Controller\RoleController;
use App\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

// Set container to create App with on AppFactory
AppFactory::setContainer(require __DIR__ . '/../../bootstrap.php');
$app = AppFactory::create();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $e, bool $displayErrorDetails) {
        $response = new Response();
        $response->getBody()->write('404 NOT FOUND');
        return $response->withStatus(404);
    }
);

$mlwr = $app->addBodyParsingMiddleware(
    ["string" => function (Request $request, RequestHandler $handler):Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (strstr($contentType, 'application/json'))
        {
            $contents = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() === JSON_ERROR_NONE)
                $request = $request->withParsedBody($contents);
        }

        return $handler->handle($request);
    }]
);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write(
        "kek"
    );
    return $response;
});

/** ROLE ENDPOINTS */
$app->get('/roles', RoleController::class . ':readRoles');
$app->post('/role/add/{name}', RoleController::class . ':addRole');
$app->put('/role/{id}/{name}', RoleController::class . ':updateRole');
$app->delete('/role/{id}', RoleController::class . ':deleteRole');

/** USER ENDPOINTS */
$app->get('/users', UserController::class . ':getUsers');
$app->get('/users/email/{email}', UserController::class . ':getUserByEmail');
$app->post('/users/add', UserController::class . ':addUser');

/** COURSE ENDPOINTS */
$app->get('/courses/get', CourseController::class . ':getCourses');
$app->post('/courses/add', CourseController::class . ':addCourse');


$app->run();