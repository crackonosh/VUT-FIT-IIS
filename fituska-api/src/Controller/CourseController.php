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
            return $this->return403response("Course code already exists.");
        }

        /** @var User */
        $lecturerUser = $this->em->find(
            User::class,
            $request->getAttribute('jwt')->sub
        );

        if ($lecturerUser == NULL)
        {
            return $this->return403response("Unable to assign not existing user.");
        }

        $course = new Course(
            $body["code"],
            $body["name"],
            $lecturerUser
        );

        $this->em->persist($course);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created new course."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function getCourses(Request $request, Response $response): Response
    {
        /** @var Course[] */
        $results = $this->em->getRepository(Course::class)->findAll();


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
            return $this->return403response("Unable to find course with specified code.");
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
            return $this->return403response("Unable to find course with specified code.");
        }

        if ($this->cs->isCourseApproved($args["code"]))
        {
            return $this->return403response("Course {$args['code']} is already approved.");
        }

        $course->setApprovedOn(new DateTime('now', new DateTimeZone("Europe/Prague")));
        
        $approver = $this->em->find(User::class, $request->getAttribute('jwt')->sub);

        if (!$approver)
        {
            return $this->return403response("Unable to approve course from non existing user.");
        }

        $course->setApprovedBy($approver);

        $this->em->persist($course);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully approved course."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getApprovedCourses(Request $request, Response $response, $args): Response
    {
        $courses = $this->em->createQueryBuilder()
            ->select("c, l, a")
            ->from(Course::class, 'c')
            ->join("c.lecturer", 'l')
            ->join("c.approved_by", 'a')
            ->where("c.approved_on IS NOT NULL");

        $results = $courses->getQuery()->getArrayResult();

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

        if ($jwtRole != 'moderator' && $jwtRole != 'admin')
        {
            return $this->return403response("Only user with 'moderator' or 'admin' role is able to list not approved courses.");
        }

        $results = $this->em->getRepository(Course::class)->findBy(array("approved_on" => null));

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
