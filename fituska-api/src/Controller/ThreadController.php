<?php
namespace App\Controller;

use App\Domain\Course;
use App\Domain\User;
use App\Domain\Thread;
use App\Domain\ThreadCategory;
use App\Services\MessageService;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ThreadController extends Controller
{
    /** @var MessageService */
    private $ms;

    public function __construct(EntityManager $em, MessageService $ms)
    {
        $this->em = $em;   
        $this->ms = $ms;
    }

    public function addThread(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "course_code" => $this->createArgument("string", $body["course_code"]),
            "title" => $this->createArgument("string", $body["title"]),
            "created_by" => $this->createArgument("integer", $body["created_by"]),
            "category" => $this->createArgument("integer", $body["category"], true),
            "message" => $this->createArgument("string", $body["message"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        /** @var Course */
        $course = $this->em->find(Course::class, $body['course_code']);

        if (!$course)
        {
            $response->getBody()->write("Unable to create thread in not existing course.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        /** @var User */
        // perform check if lecturer or enrolled student in course
        $createdBy = $this->em->find(User::class, $body['created_by']);

        if (!$createdBy)
        {
            $response->getBody()->write("Unable to assign not existing user.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        /** @var Category */
        // add check that category is from current Course
        $category = NULL;
        if ($body['category'])
            $category = $this->em->find(ThreadCategory::class, $body['category']);
        
        $thread = new Thread(
            $course,
            $body['title'],
            $createdBy,
            $category
        );

        $this->em->persist($thread);
        $this->em->flush();

        $this->ms->addMessage($thread, $createdBy, $body["message"]);

        $response->getBody()->write("Successfully created new thread.");
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
            "messages" => array()
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
            $response->getBody()->write("Unable to find threads for non existing course code.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        /** @var Thread[] */
        $threads = $this->em->getRepository(Thread::class)->findBy(array("course" => $course));

        if(count($threads) == 0)
        {
            $response->getBody()->write("No thread found for this course.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

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
            $response->getBody()->write("No results found.");
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
            $response->getBody()->write("Unable to close thread for specified ID. No thread found.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        // add setClosedBy that needs JWT for fetching user
        $thread->setClosedOn(new DateTime('now', new DateTimeZone("Europe/Prague")));

        // ADD SOMETHING FOR GAMIFICATION

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
            $response->getBody()->write("Unable to delete thread for specified ID. No thread found.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        // add check for author/lecturer from JWT

        $this->em->remove($thread);
        $this->em->flush();

        $response->getBody()->write("Successfully deleted a thread.");
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}