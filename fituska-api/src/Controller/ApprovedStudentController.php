<?php
namespace App\Controller;

use App\Domain\ApprovedStudent;
use App\Domain\Course;
use App\Domain\User;
use App\Services\ApprovedStudentService;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ApprovedStudentController extends Controller
{
    /** @var ApprovedStudentService */
    private $ass;

    public function __construct(EntityManager $em, ApprovedStudentService $ass)
    {
        $this->em = $em;   
        $this->ass = $ass;
    }

    public function addApplication(Request $request, Response $response, $args): Response
    {
        /** @var User */
        $applicant = $this->em->find(
            User::class,
            $request->getAttribute('jwt')->sub
        );

        if (!$applicant)
        {
            return $this->return403response("Unable to add application for non-existing user.");
        }

        $course = $this->em->find(
            Course::class,
            $args['code']
        );

        if (!$course)
        {
            return $this->return403response("Unable to add application for non-existing course.");
        }

        if ($this->ass->alreadyHasApplication($applicant, $course))
        {
            return $this->return403response("Unable to add multiple applications for course.");
        }

        $application = new ApprovedStudent(
            $applicant,
            $course
        );

        $this->em->persist($application);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully added an application for enrollment in course {$args['code']}."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function getApplications(Request $request, Response $response, $args): Response
    {
        /** @var User */
        $user = $this->em->find(User::class, $request->getAttribute('jwt')->sub);

        /** @var Course */
        $course = $this->em->find(Course::class, $args['code']);
        if (!$course)
        {
            return $this->return403response("Unable to fetch applications for not existing course.");
        }

        if ($user->getID() != $course->getLecturer()->getID())
        {
            return $this->return403response("Only lecturer of course is able to view its applications");
        }

        $applications = array();
        /** @var ApprovedStudent */
        foreach ($course->getApplications() as $application)
        {
            $tmp = array(
                'id' => $application->getID(),
                'student' => array(
                    'id' => $application->getStudent()->getID(),
                    'name' => $application->getStudent()->getName()
                ),
                'status' => $application->getStatus()
            );
            array_push($applications, $tmp);
        }

        $response->getBody()->write(json_encode($applications));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function approveApplication(Request $request, Response $response, $args): Response
    {
        /** @var ApprovedStudent */
        $application = $this->em->find(ApprovedStudent::class, $args['id']);

        if (!$application)
        {
            return $this->return403response("Unable to approve not existing application.");
        }

        if (
            $application->getCourse()->getLecturer()->getID() != $request->getAttribute('jwt')->sub
        ){
            return $this->return403response("Only lecturer of course is able to approve students.");
        }

        $application->setStatus(true);
        $application->setApprovedBy($this->em->find(
            User::class, $request->getAttribute('jwt')->sub
        ));
        $application->setApprovedOn(new DateTime('now', new DateTimeZone('Europe/Prague')));

        $this->em->persist($application);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully approved application."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function revokeApplication(Request $request, Response $response, $args): Response
    {
        /** @var ApprovedStudent */
        $application = $this->em->find(ApprovedStudent::class, $args['id']);

        if (!$application)
        {
            return $this->return403response("Unable to revoke not existing application.");
        }

        if (
            $application->getCourse()->getLecturer()->getID() != $request->getAttribute('jwt')->sub
        ){
            return $this->return403response("Only lecturer of course is able to revoke students.");
        }

        $application->setStatus(false);
        $application->setApprovedBy($this->em->find(
            User::class, $request->getAttribute('jwt')->sub
        ));
        $application->setApprovedOn(new DateTime('now', new DateTimeZone('Europe/Prague')));

        $this->em->persist($application);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully revoked application."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}