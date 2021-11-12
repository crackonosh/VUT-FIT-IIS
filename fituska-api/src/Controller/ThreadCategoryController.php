<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\ThreadCategory;
use App\Domain\User;
use App\Domain\Course;
use App\Services\ThreadCategoryService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class ThreadCategoryController
{
    /** @var EntityManager */
    private $em;
    /** @var string */
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addThreadCategory(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "name" => createArgument("string", $body["name"]),
            "created_by" => createArgument("integer", $body["created_by"]),
            "course_code" => createArgument("string", $body["course_code"])
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        if (!ThreadCategoryService::isNameUniqueForCourse($this->em, $body["name"], $body["course_code"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Category with given name already exists for this course.");
            return $response;
        }

        /** @var User */
        $user = $this->em->getRepository("App\Domain\User")->find($body["created_by"]); // should be taken from JWT

        if (!$user)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign not existing user.");
            return $response;
        }

        /** @var Course */
        $course = $this->em->getRepository("App\Domain\Course")->find($body["course_code"]);

        if (!$course)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign not existing course.");
            return $response;
        }

        $tCategory = new ThreadCategory(
            $body["name"],
            $user,
            $course
        );

        $this->em->persist($tCategory);
        $this->em->flush();

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new thread category.");
        return $response;
    }
}