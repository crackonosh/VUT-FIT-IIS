<?php

// leave this here if you don't wanna get mad about `uSe CoNtInUe 2 hur dur dur` warning msgs (might break something :pausechamp:) :loudasleeper:
error_reporting(E_ALL ^ E_WARNING);

use App\Controller\ApprovedStudentController;
use App\Controller\CourseController;
use App\Controller\MessageController;
use App\Controller\RoleController;
use App\Controller\UserController;
use App\Controller\ThreadCategoryController;
use App\Controller\ThreadController;
use App\Services\SetupService;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpMethodNotAllowedException;

require __DIR__ . '/../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');

AppFactory::setContainer(require __DIR__ . '/../../bootstrap.php');
$app = AppFactory::create();
$container = $app->getContainer();

/** MIDDLEWARE SECTION */
$app->addBodyParsingMiddleware(
    include_once __DIR__ . '/../Middleware/BodyParsingMiddleware.php'
);

$displayErrorDetails = $container->get('settings')['displayErrorDetails'];
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);
if (!$displayErrorDetails)
{
    $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        include_once __DIR__ . '/../Handler/HttpNotFoundHandler.php'
    );
}
$errorMiddleware->setErrorHandler(
    HttpMethodNotAllowedException::class,
    include_once __DIR__ . '/../Handler/HttpMethodNotAllowedHandler.php'
);

// used for data setup when newly deployed to server
$app->get('/setup', SetupService::class . ':setup');

/** SIGNUP/LOGIN ENDPOINTS */
$app->post('/signup', UserController::class . ':addUser');
$app->post('/login', UserController::class . ':loginUser');


/********************** PUBLIC ENDPOINTS *****************************/
// user endpoints
$app->get('/users', UserController::class . ':getUsers');
$app->get('/users/{id}/get', UserController::class . ':getUser');
$app->get('/users/email/{email}/get', UserController::class . ':getUsersByEmail'); // maybe delete this endpoint?
$app->get('/users/name/{name}/get', UserController::class . ':getUsersByName');

// course endpoints
$app->get('/courses/get', CourseController::class . ':getCourses');
$app->get('/courses/get/approved', CourseController::class . ':getApprovedCourses');
$app->get('/courses/{code}/get', CourseController::class . ':getCourseByCode');

// thread endpoints
$app->get('/courses/{code}/threads/get', ThreadController::class . ':getThreadsForCourse');
$app->get('/threads/title/{title}/get', ThreadController::class . ':getThreadsByTitle');
$app->get('/threads/id/{id}/get', ThreadController::class . ':getThread');
/********************** PUBLIC ENDPOINTS *****************************/


/********************** PROTECTED ENDPOINTS **************************/
$app->group('', function (RouteCollectorProxy $group) {
    /** ROLE ENDPOINTS */
    $group->get('/roles', RoleController::class . ':readRoles');
    $group->post('/roles/add/{name}', RoleController::class . ':addRole');
    $group->put('/roles/{id}/{name}', RoleController::class . ':updateRole');
    $group->delete('/roles/{id}', RoleController::class . ':deleteRole');

    /** USER ENDPOINTS */
    $group->put('/users/{userID}/role/{roleID}', UserController::class . ':changeRole');

    /** COURSE ENDPOINTS */
    $group->get('/courses/get/not-approved', CourseController::class . ':getNotApprovedCourses');
    $group->post('/courses/add', CourseController::class . ':addCourse');
    $group->put('/courses/{code}/approve', CourseController::class . ':approveCourse');

    /** STUDENT APPLICATION ENDPOINTS */
    $group->get('/courses/{code}/applications/get', ApprovedStudentController::class . ':getApplications');
    $group->post('/courses/{code}/application/add', ApprovedStudentController::class . ':addApplication');
    $group->put('/applications/{id}/approve', ApprovedStudentController::class . ':approveApplication');
    $group->put('/applications/{id}/revoke', ApprovedStudentController::class . ':revokeApplication');

    /** THREAD CATEGORY ENDPOINTS */
    $group->get('/courses/{code}/get/categories', ThreadCategoryController::class . ':readThreadCategories');
    $group->post('/categories/add', ThreadCategoryController::class . ':addThreadCategory');
    $group->put('/categories/{id}/update', ThreadCategoryController::class . ':updateThreadCategory');
    $group->delete('/categories/{id}/delete', ThreadCategoryController::class . ':deleteThreadCategory');

    /** THREAD ENDPOINTS */
    $group->post('/threads/add', ThreadController::class . ':addThread');
    $group->put('/threads/{id}/close', ThreadController::class . ':closeThread');
    $group->delete('/threads/{id}/delete', ThreadController::class . ':deleteThread');

    /** THREAD MESSAGE ENDPOINTS */
    $group->post('/threads/{id}/message/add', MessageController::class . ':addMessage');
    $group->post('/messages/{id}/vote', MessageController::class . ':addVote');
    $group->post('/messages/compensate', MessageController::class . ':compensateMessages');
    $group->put('/messages/{id}/update-score', MessageController::class . ':updateScoreForMessage');

})->add(include_once __DIR__ . '/../Middleware/JwtMiddleware.php');
/********************** PROTECTED ENDPOINTS **************************/


$app->run();
