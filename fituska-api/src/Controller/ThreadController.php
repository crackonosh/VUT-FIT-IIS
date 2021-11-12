<?php
namespace App\Controller;

use App\Domain\Course;
use App\Domain\User;
use App\Domain\Thread;
use App\Domain\ThreadCategory;
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

        $response->getBody()->write("Successfully created new thread.");
        return $response;
    }
}