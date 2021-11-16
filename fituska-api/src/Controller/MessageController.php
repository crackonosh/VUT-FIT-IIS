<?php
namespace App\Controller;

use App\Domain\Thread;
use App\Domain\User;
use App\Services\MessageService;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class MessageController
{
    /** @var EntityManager */
    private $em;
    /** @var string */
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addMessage(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "message" => createArgument("string", $body["message"]),
            "created_by" => createArgument("integer", $body["created_by"])
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to create message. Thread not found.");
            return $response;
        }

        /** @var User */
        $author = $this->em->find(User::class, $body["created_by"]);
        if (!$author)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to create message. User ID not found.");
            return $response;
        }

        MessageService::addMessage($this->em, $thread, $author, $body["message"]);

        $response->getBody()->write("Successfully created a message.");
        return $response;
    }
}
