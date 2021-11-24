<?php
namespace App\Controller;

use App\Domain\Thread;
use App\Domain\User;
use App\Services\MessageService;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


class MessageController extends Controller
{
    /** @var MessageService */
    private $ms;
    
    public function __construct(EntityManager $em, MessageService $ms)
    {
        $this->em = $em;
        $this->ms = $ms;
    }

    public function addMessage(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "message" => $this->createArgument("string", $body["message"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        /** @var Thread */
        $thread = $this->em->find(Thread::class, $args["id"]);
        if (!$thread)
        {
            return $this->return403response("Unable to create message. Thread not found.");
        }

        $authorID = $request->getAttribute('jwt')->sub;
        /** @var User */
        $author = $this->em->find(User::class, $authorID);
        if (!$author)
        {
            return $this->return403response("Unable to create message. User ID not found.");
        }

        if ($this->ms->hasMessageInThread($thread, $author))
        {
            return $this->return403response("Already written a message to a thread.");
        }

        $this->ms->addMessage($thread, $author, $body["message"]);

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created a message."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }
}
