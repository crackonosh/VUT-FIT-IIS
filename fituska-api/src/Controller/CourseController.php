<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\Course;
use App\Domain\User;
use App\Services\CourseService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use DateTime;
use DateTimeZone;

class CourseController extends Controller
{
    /** @var CourseService */
    private $cs;

    public function __construct(EntityManager $em, CourseService $cs)
    {
        $this->em = $em;
        $this->cs = $cs;
    }

    public function addCourse(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "code" => $this->createArgument("string", $body["code"]),
            "name" => $this->createArgument("string", $body["name"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        if ($this->cs->isCodeUnique($body["code"]))
        {
            $response->getBody()->write("Course code already exists");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(403);
        }

        /** @var User */
        $lecturerUser = $this->em->find(
            User::class,
            $request->getAttribute('jwt')->sub
        );

        if ($lecturerUser == NULL)
        {
            $response->getBody()->write("Unable to assign not existing user.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $course = new Course(
            $body["code"],
            $body["name"],
            $lecturerUser
        );

        $this->em->persist($course);
        $this->em->flush();

        $response->getBody()->write("Successfully created new course.");
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function getCourses(Request $request, Response $response): Response
    {
        /** @var Course[] */
        $results = $this->em->getRepository(Course::class)->findAll();

        if (!count($results))
        {
            $response->getBody()->write("No courses were found.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array();
        foreach ($results as $course)
        {
            $lecturerUser = $course->getLecturer();
            $lecturerData = array(
                "id" => $lecturerUser->getID(),
                "name" => $lecturerUser->getName()
            );

            $approvedByData = NULL;
            if ($course->getApprovedBy())
            {
                $approvedByData = array(
                    "id" => $course->getApprovedBy()->getID(),
                    "name" => $course->getApprovedBy()->getName()
                );
            }

            $data = array(
                "code" => $course->getCode(),
                "name" => $course->getName(),
                "lecturer" => $lecturerData,
                "approved_by" => $approvedByData,
                "created_on" => $course->getCreatedOn()->format("Y-m-d H:i:s"),
                "approved_on" => $course->getApprovedOn() ? $course->getApprovedOn()->format("Y-m-d H:i:s") : NULL
            );
            array_push($msg, $data);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getCourseByCode(Request $request, Response $response, $args): Response
    {
        /** @var Course */
        $course = $this->em->find(Course::class, $args["code"]);

        if (!$course)
        {
            $response->getBody()->write("Unable to find course with specified code.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $lecturerData = array(
            "id" => $course->getLecturer()->getID(),
            "name" => $course->getLecturer()->getName()
        );

        $approvedByData = NULL;
        if ($course->getApprovedBy())
        {
            $approvedByData = array(
                "id" => $course->getApprovedBy()->getID(),
                "name" => $course->getApprovedBy()->getName()
            );
        }
        
        $msg = array(
            "code" => $course->getCode(),
            "name" => $course->getName(),
            "lecturer" => $lecturerData,
            "approved_by" => $approvedByData,
            "created_on" => $course->getCreatedOn()->format("Y-m-d H:i:s"),
            "approved_on" => $course->getApprovedOn() ? $course->getApprovedOn()->format("Y-m-d H:i:s") : NULL
        );

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function approveCourse(Request $request, Response $response, $args): Response
    {
        /** @var Course */
        $course = $this->em->find(Course::class, $args["code"]);

        if (!$course)
        {
            $response->getBody()->write("Unable to find course with specified code.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        if ($this->cs->isCourseApproved($args["code"]))
        {
            $response->getBody()->write("Course {$args['code']} is already approved.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(403);
        }

        $course->setApprovedOn(new DateTime('now', new DateTimeZone("Europe/Prague")));
        
        $approver = $this->em->find(User::class, $request->getAttribute('jwt')->sub);

        if (!$approver)
        {
            $response->getBody()->write(json_encode(array(
                "message" => "Unable to approve course from non existing user."
            )));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(403);
        }

        $course->setApprovedBy($approver);

        $this->em->persist($course);
        $this->em->flush();

        $response->getBody()->write("Successfully approved course.");
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getApprovedCourses(Request $request, Response $response, $args): Response
    {
        $courses = $this->em->createQueryBuilder()
            ->select("c, u")
            ->from(Course::class, 'c')
            ->join("c.lecturer", 'u')
            ->where("c.approved_on IS NOT NULL");

        $results = $courses->getQuery()->getArrayResult();

        if (count($results) == 0)
        {
            $response->getBody()->write("No approved courses were found.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array();
        /** @var Course */
        foreach ($results as $course)
        {
            $lecturerData = array(
                "id" => $course["lecturer"]["id"],
                "name" => $course["lecturer"]["name"]
            );

            /** fix fetching approved_by data */
            $approvedByData = NULL;
            if (isset($course["approved_by"]))
            {
                $approvedByData = array(
                    "id" => $course["approved_by"]["id"],
                    "name" => $course["approved_by"]["name"]
                );
            }

            $tmp = array(
                "code" => $course["code"],
                "name" => $course["name"],
                "lecturer" => $lecturerData,
                "approved_by" => $approvedByData,
                "approved_on" => $course["approved_on"] ? $course["approved_on"]->format("Y-m-d H:i:s") : NULL,
                "created_on" => $course["created_on"]->format("Y-m-d H:i:s")
            );

            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getNotApprovedCourses(Request $request, Response $response, $args): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'moderator' || $jwtRole != 'admin')
        {
            $response->getBody()->write(json_encode(array(
                "message" => "Only user with 'moderator' or 'admin' role is able to list not approved courses."
            )));

            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(403);
        }

        $results = $this->em->getRepository(Course::class)->findBy(array("approved_on" => null));

        if (!count($results))
        {
            $response->getBody()->write("No not approved courses were found.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array();
        /** @var Course */
        foreach ($results as $course)
        {
            $lecturerUser = $course->getLecturer();
            $lecturerData = array(
                "id" => $lecturerUser->getID(),
                "name" => $lecturerUser->getName()
            );

            $approvedByData = NULL;
            if ($course->getApprovedBy())
            {
                $approvedByData = array(
                    "id" => $course->getApprovedBy()->getID(),
                    "name" => $course->getApprovedBy()->getName()
                );
            }

            $data = array(
                "code" => $course->getCode(),
                "name" => $course->getName(),
                "lecturer" => $lecturerData,
                "approved_by" => $approvedByData,
                "created_on" => $course->getCreatedOn()->format("Y-m-d H:i:s"),
                "approved_on" => $course->getApprovedOn() ? $course->getApprovedOn()->format("Y-m-d H:i:s") : NULL
            );
            array_push($msg, $data);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}