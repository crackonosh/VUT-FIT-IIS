<?php

// leave this here if you don't wanna get mad about `uSe CoNtInUe 2 hur dur dur` warning msgs (might break something :pausechamp:) :loudasleeper:
error_reporting(E_ALL ^ E_WARNING); 

use App\Controller\CourseController;
use App\Controller\MessageController;
use App\Controller\RoleController;
use App\Controller\UserController;
use App\Controller\ThreadCategoryController;
use App\Controller\ThreadController;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

// Set container to create App with on AppFactory
AppFactory::setContainer(require __DIR__ . '/../../bootstrap.php');
$app = AppFactory::create();
$container = $app->getContainer();

/** MIDDLEWARE SECTION */
$app->addBodyParsingMiddleware(
    include_once __DIR__ . '/../Middleware/BodyParsingMiddleware.php'
);
$app->add(include_once __DIR__ . '/../Middleware/JwtMiddleware.php');

$displayErrorDetails = $container->get('settings')['displayErrorDetails'];
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);
if (!$displayErrorDetails)
{
    $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        include_once __DIR__ . '/../Handler/HttpNotFoundHandler.php'
    );
}

/** ROLE ENDPOINTS */
// those endpoints should be working only if admin
$app->get('/roles', RoleController::class . ':readRoles');
$app->post('/roles/add/{name}', RoleController::class . ':addRole');
$app->put('/roles/{id}/{name}', RoleController::class . ':updateRole');
$app->delete('/roles/{id}', RoleController::class . ':deleteRole');

/** USER ENDPOINTS */
$app->get('/users', UserController::class . ':getUsers');
$app->get('/users/email/{email}', UserController::class . ':getUserByEmail');
$app->get('/users/name/{name}', UserController::class . ':getUserByName');
$app->post('/users/add', UserController::class . ':addUser');
$app->put('/users/{userID}/role/{roleID}', UserController::class . ':changeRole'); // only moderator/admin should be able to change roles of others

/** COURSE ENDPOINTS */
// public endpoints
$app->get('/courses/get', CourseController::class . ':getCourses');
$app->get('/courses/{code}/get', CourseController::class . ':getCourseByCode');
$app->get('/courses/get/approved', CourseController::class . ':getApprovedCourses');
$app->get('/courses/get/not-approved', CourseController::class . ':getNotApprovedCourses'); // only for moderators+
$app->post('/courses/add', CourseController::class . ':addCourse');
$app->put('/courses/{code}/approve', CourseController::class . ':approveCourse'); // this endpoint needs JWT to function correctly ("approved_by" should be taken as ID from Authorization header, now it sets "approved_on" only)

/** THREAD CATEGORY ENDPOINTS */
$app->get('/courses/{code}/get/categories', ThreadCategoryController::class . ':readThreadCategories');
$app->post('/categories/add', ThreadCategoryController::class . ':addThreadCategory'); // only lecturer should be able to add category to course (his ID will be taken from JWT afterwards)
$app->put('/categories/{id}/update', ThreadCategoryController::class . ':updateThreadCategory'); // only lecturer should be able to change categories
$app->delete('/categories/{id}/delete', ThreadCategoryController::class . ':deleteThreadCategory'); // this endpoint needs JWT to function correctly (Check that person trying to delete the category is lecturer of course)

/** THREAD ENDPOINTS */
$app->get('/courses/{code}/threads/get', ThreadController::class . ':getThreadsForCourse');
$app->get('/threads/title/{title}/get', ThreadController::class . ':getThreadsByTitle');
$app->get('/threads/id/{id}/get', ThreadController::class . ':getThread'); // add fetching thread msgs
$app->post('/threads/add', ThreadController::class . ':addThread'); // created by will be taken from JWT
$app->put('/threads/{id}/close', ThreadController::class . ':closeThread'); // missing JWT and gamification
$app->delete('/threads/{id}/delete', ThreadController::class . ':deleteThread'); // only author or lecturer

/** THREAD MESSAGE ENDPOINTS */
// users shouldn't delete/update messages because they'll get points for them ?
$app->post('/threads/{id}/message/add', MessageController::class . ':addMessage'); // created_by JWT and check if enrolled in course

$app->run();
