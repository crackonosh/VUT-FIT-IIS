<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\Course;
use App\Domain\User;
use App\Services\CourseService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class CourseController
{
    /** @var EntityManager */
    private $em;
    /** @var string */
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addCourse(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "code" => createArgument("string", $body["code"]),
            "name" => createArgument("string", $body["name"]),
            "lecturer" => createArgument("integer", $body["lecturer"]),
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        if (CourseService::isCodeUnique($this->em, $body["code"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Course code already exists");
            return $response;
        }

        /** @var User */
        $lecturerUser = $this->em->getRepository("App\Domain\User")->find($body["lecturer"]);

        if ($lecturerUser == NULL)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign not existing user.");
            return $response;
        }

        $course = new Course(
            $body["code"],
            $body["name"],
            $lecturerUser
        );

        $this->em->persist($course);
        $this->em->flush();

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new course.");
        return $response;
    }

    public function getCourses(Request $request, Response $response): Response
    {
        $results = $this->em->getRepository("App\Domain\Course")->findAll();

        $msg = array();
        /** @var Course */
        foreach ($results as $course)
        {
            $lecturerUser = $course->getLecturer();
            $lecturerData = array(
                "id" => $lecturerUser->getID(),
                "name" => $lecturerUser->getName()
            );

            $data = array(
                "code" => $course->getCode(),
                "name" => $course->getName(),
                "lecturer" => $lecturerData,
                "created_on" => $course->getCreatedOn()->format("Y-m-d H:i:s")
            );
            array_push($msg, $data);
        }
        $response->getBody()->write(json_encode($msg));
        return $response;
    }
}