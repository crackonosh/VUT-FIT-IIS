<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\ThreadCategory;
use App\Domain\User;
use App\Domain\Course;
use App\Services\ThreadCategoryService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


class ThreadCategoryController extends Controller
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
            "name" => $this->createArgument("string", $body["name"]),
            "created_by" => $this->createArgument("integer", $body["created_by"]),
            "course_code" => $this->createArgument("string", $body["course_code"])
        );

        $this->parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        if (!ThreadCategoryService::isNameUniqueForCourse($this->em, $body["name"], $body["course_code"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Category with given name already exists for this course.");
            return $response;
        }

        /** @var User */
        $user = $this->em->find(User::class, $body["created_by"]); // should be taken from JWT

        if (!$user)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign not existing user.");
            return $response;
        }

        /** @var Course */
        $course = $this->em->find(Course::class, $body["course_code"]);

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

    public function readThreadCategories(Request $request, Response $response, $args): Response
    {
        $results = $this->em->getRepository(ThreadCategory::class)->findBy(array("course" => $args["code"]));

        if (!count($results))
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("No thread categories found for given course code.");
            return $response;
        }

        $msg = array();
        /** @var ThreadCategory */
        foreach ($results as $tCategory)
        {
            $tmp = array(
                "id" => $tCategory->getID(),
                "name" => $tCategory->getName()
            );

            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response;
    }

    public function updateThreadCategory(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "name" => createArgument("string", $body["name"])
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        /** @var ThreadCategory */
        $tCategory = $this->em->find(ThreadCategory::class, $args["id"]);

        if (!$tCategory)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find thread category with given ID.");
            return $response;
        }

        $tCategory->setName($body["name"]);

        $this->em->persist($tCategory);
        $this->em->flush();

        $response->getBody()->write("Successfully updated name of category.");
        return $response;
    }

    public function deleteThreadCategory(Request $request, Response $response, $args): Response
    {
        /** @var ThreadCategory */
        $tCategory = $this->em->find(ThreadCategory::class, $args["id"]);

        if (!$tCategory)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to delete not existing category.");
            return $response;
        }

        $this->em->remove($tCategory);
        $this->em->flush();

        $response->getBody()->write("Successfully deleted thread category.");
        return $response;
    }
}