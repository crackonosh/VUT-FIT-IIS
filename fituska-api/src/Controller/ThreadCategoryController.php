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
    /** @var ThreadCategoryService */
    private $tcs;

    public function __construct(EntityManager $em, ThreadCategoryService $tcs)
    {
        $this->em = $em;
        $this->tcs = $tcs;
    }

    public function addThreadCategory(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "name" => $this->createArgument("string", $body["name"]),
            "course_code" => $this->createArgument("string", $body["course_code"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        if (!$this->tcs->isNameUniqueForCourse($body["name"], $body["course_code"]))
        {
            return $this->return403response("Category with given name already exists for this course.");
        }

        /** @var User */
        $user = $this->em->find(User::class, $request->getAttribute('jwt')->sub);
        if (!$user)
        {
            return $this->return403response("Unable to assign not existing user.");
        }

        /** @var Course */
        $course = $this->em->find(Course::class, $body["course_code"]);
        if (!$course)
        {
            return $this->return403response("Unable to assign not existing course.");
        }

        if ($course->getLecturer()->getID() != $user->getID())
        {
            return $this->return403response("Only lecturer of course is able to add thread categories.");
        }

        $tCategory = new ThreadCategory(
            $body["name"],
            $user,
            $course
        );

        $this->em->persist($tCategory);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created new thread category."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function readThreadCategories(Request $request, Response $response, $args): Response
    {
        /** @var Course */
        $course = $this->em->find(Course::class, $args['code']);
        if (!$course)
        {
            $response->getBody()->write(json_encode(array(
                "message" => "Unable to read thread categories for not existing course."
            )));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        if ($course->getLecturer()->getID() != $request->getAttribute('jwt')->sub)
        {
            return $this->return403response("Only lecturer of course is able to list thread categories.");
        }

        $results = $this->em->getRepository(ThreadCategory::class)->findBy(array("course" => $args["code"]));

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
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function updateThreadCategory(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "name" => $this->createArgument("string", $body["name"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        /** @var ThreadCategory */
        $tCategory = $this->em->find(ThreadCategory::class, $args["id"]);

        if (!$tCategory)
        {
            return $this->return403response("Unable to find thread category with given ID.");
        }

        $lecturerID = $tCategory->getCourse()->getLecturer()->getID();

        if ($lecturerID != $request->getAttribute('jwt')->sub)
        {
            return $this->return403response("Only lecturer of course is able to update it's thread categories.");
        }

        $tCategory->setName($body["name"]);

        $this->em->persist($tCategory);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully updated name of category."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function deleteThreadCategory(Request $request, Response $response, $args): Response
    {
        /** @var ThreadCategory */
        $tCategory = $this->em->find(ThreadCategory::class, $args["id"]);

        if (!$tCategory)
        {
            return $this->return403response("Unable to delete not existing category.");
        }

        $lecturerID = $tCategory->getCourse()->getLecturer()->getID();

        if ($lecturerID != $request->getAttribute('jwt')->sub)
        {
            return $this->return403response("Only lecturer of course is able to delete it's thread categories.");
        }

        $this->em->remove($tCategory);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully deleted thread category."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}