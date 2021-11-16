<?php
namespace App\Controller;

use App\Domain\Course;
use App\Domain\User;
use App\Domain\Thread;
use App\Domain\ThreadCategory;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

use function DI\create;

require_once __DIR__ . '/../Functions.php';

class ThreadController
{
    /** @var EntityManager */
    private $em;
    /** @var string */
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addThread(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "course_code" => createArgument("string", $body["course_code"]),
            "title" => createArgument("string", $body["title"]),
            "created_by" => createArgument("integer", $body["created_by"]),
            "category" => createArgument("integer", $body["category"], true)
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        /** @var Course */
        $course = $this->em->find(Course::class, $body['course_code']);

        if (!$course)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to create thread in not existing course.");
            return $response;
        }

        /** @var User */
        // perform check if lecturer or enrolled student in course
        $createdBy = $this->em->find(User::class, $body['created_by']);

        if (!$createdBy)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign not existing user.");
            return $response;
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

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new thread.");
        return $response;
    }

    public function getThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);

        if (!$thread)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find thread with specified ID");
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
        return $response;
    }

    public function getThreadsForCourse(Request $request, Response $response, $args): Response
    {
        /** @var Course */
        $course = $this->em->find(Course::class, $args["code"]);
        if (!$course)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find threads for non existing course code.");
            return $response;
        }

        /** @var Thread[] */
        $threads = $this->em->getRepository(Thread::class)->findBy(array("course" => $course));

        if(count($threads) == 0)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("No thread found for this course.");
            return $response;
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
        return $response;
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
            $response = $response->withStatus(404);
            $response->getBody()->write("No results found.");
            return $response;
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
        return $response;
    }

    public function closeThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to close thread for specified ID. No thread found.");
            return $response;
        }

        // add setClosedBy that needs JWT for fetching user
        $thread->setClosedOn(new DateTime('now', new DateTimeZone("Europe/Prague")));

        // ADD SOMETHING FOR GAMIFICATION

        $this->em->persist($thread);
        $this->em->flush();

        $response->getBody()->write("Successfully closed a thread.");
        return $response;
    }

    public function deleteThread(Request $request, Response $response, $args): Response
    {
        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to delete thread for specified ID. No thread found.");
            return $response;
        }

        // add check for author/lecturer from JWT

        $this->em->remove($thread);
        $this->em->flush();

        $response->getBody()->write("Successfully deleted a thread.");
        return $response;
    }
}