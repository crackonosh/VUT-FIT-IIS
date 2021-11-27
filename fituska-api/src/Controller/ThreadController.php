<?php
namespace App\Controller;

use App\Domain\Course;
use App\Domain\MessageAttachment;
use App\Domain\User;
use App\Domain\Thread;
use App\Domain\ThreadCategory;
use App\Services\ApprovedStudentService;
use App\Services\CourseService;
use App\Services\MessageService;
use App\Services\ThreadService;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ThreadController extends Controller
{
    /** @var MessageService */
    private $ms;
    /** @var ThreadService */
    private $ts;
    /** @var CourseService */
    private $cs;
    /** @var ApprovedStudentService */
    private $ass;

    public function __construct(
        EntityManager $em,
        MessageService $ms,
        ThreadService $ts,
        ApprovedStudentService $ass
    ){
        $this->em = $em;   
        $this->ms = $ms;
        $this->ts = $ts;
        $this->ass = $ass;
    }

    public function addThread(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "course_code" => $this->createArgument("string", $body["course_code"]),
            "title" => $this->createArgument("string", $body["title"]),
            "category" => $this->createArgument("integer", $body["category"], true),
            "message" => $this->createArgument("string", $body["message"]),
            "attachments" => $this->createArgument("array", $body['attachments'])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        /** @var Course */
        $course = $this->em->find(Course::class, $body['course_code']);

        if (!$course)
        {
            return $this->return403response("Unable to create thread in not existing course.");
        }

        /** @var User */
        $createdBy = $this->em->find(User::class, $request->getAttribute('jwt')->sub);
        if (!$createdBy)
        {
            return $this->return403response("Unable to assign not existing user.");
        }
        if (
            $course->getLecturer()->getID() != $createdBy->getID() &&
            $this->ass->isApproved($createdBy, $course)
        )
        {
            return $this->return403response("Only course lecturer and approved enrolled students are able to create new threads.");
        }

        /** @var Category */
        $category = NULL;
        if ($body['category'])
        {
            /** @var ThreadCategory */
            $category = $this->em->find(ThreadCategory::class, $body['category']);
            if ($category->getCourse()->getCode() != $course->getCode())
            {
                return $this->return403response("Unable to use thread category that is defined for different course.");
            }
        }
        
        $thread = new Thread(
            $course,
            $body['title'],
            $createdBy,
            $category
        );

        $this->em->persist($thread);
        $this->em->flush();

        $attachments = $this->ms->processAttachments($body['attachments']);

        $msg = $this->ms->addMessage($thread, $createdBy, $body["message"]);

        foreach ($attachments as $filename)
        {
            $msgAtt = new MessageAttachment(
                $filename,
                $msg
            );
            $this->em->persist($msgAtt);
        }

        // save all attachments
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created new thread."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function getThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);

        if (!$thread)
        {
            $response->getBody()->write("Unable to find thread with specified ID");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array(
            "id" => $thread->getID(),
            "title" => $thread->getTitle(),
            "is_closed" => $thread->getClosedOn() ? true : false,
            "author" => array(
                "id" => $thread->getCreatedBy()->getID(),
                "name" => $thread->getCreatedBy()->getName()
            ),
            "messages" => $this->ts->prepareMessagesForResponse($thread->getMessages())
        );

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getThreadsForCourse(Request $request, Response $response, $args): Response
    {
        /** @var Course */
        $course = $this->em->find(Course::class, $args["code"]);
        if (!$course)
        {
            return $this->return403response("Unable to find threads for non existing course code.");
        }

        /** @var Thread[] */
        $threads = $this->em->getRepository(Thread::class)->findBy(array("course" => $course));

        $msg = array();
        foreach ($threads as $thread) {
            $tmp = array(
                "id" => $thread->getID(),
                "title" => $thread->getTitle(),
                "author" => array(
                    "id" => $thread->getCreatedBy()->getID(),
                    "name" => $thread->getCreatedBy()->getName()
                ),
                "is_closed" => $thread->getClosedOn() == NULL ? false : true
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getThreadsByTitle(Request $request, Response $response, $args): Response
    {
        $qb = $this->em->createQueryBuilder()
            ->select("t, a")
            ->from(Thread::class, 't')
            ->join('t.created_by', 'a')
            ->where("t.title LIKE '%" . $args["title"] . "%'")
            ->orderBy('t.closed_on');

        $results = $qb->getQuery()->getArrayResult();

        if (count($results) == 0)
        {
            $response->getBody()->write(json_encode(array(
                "message" => "No results found."
            )));
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array();
        foreach ($results as $result)
        {
            $tmp = array(
                "id" => $result["id"],
                "title" => $result["title"],
                "is_closed" => $result["closed_on"] == NULL ? false : true,
                "author" => array(
                    "id" => $result["created_by"]["id"],
                    "name" => $result["created_by"]["name"]
                )
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function closeThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            return $this->return403response("Unable to close thread for specified ID. No thread found.");
        }

        /** @var User */
        $user = $this->em->find(User::class, $request->getAttribute('jwt')->sub);
        if ($user->getID() != $thread->getCourse()->getLecturer()->getID())
        {
            return $this->return403response("Only lecturer of course is able to close it's threads.");
        }

        $thread->setClosedOn(new DateTime('now', new DateTimeZone("Europe/Prague")));
        $thread->setClosedBy($user);

        $this->em->persist($thread);
        $this->em->flush();

        $response->getBody()->write("Successfully closed a thread.");
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function deleteThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            return $this->return403response("Unable to delete thread for specified ID. No thread found.");
        }

        if (
            $request->getAttribute('jwt')->sub != $thread->getCreatedBy()->getID() ||
            $request->getAttribute('jwt')->sub != $thread->getCourse()->getLecturer()->getID()
        ){
            return $this->return403response("Only author of thread or lecturer of course is able to delete thread.");
        }

        $this->em->remove($thread);
        $this->em->flush();

        $response->getBody()->write("Successfully deleted a thread.");
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}