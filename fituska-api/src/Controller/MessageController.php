<?php
namespace App\Controller;

use App\Domain\Thread;
use App\Domain\User;
use App\Services\ApprovedStudentService;
use App\Services\MessageService;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;


class MessageController extends Controller
{
    /** @var MessageService */
    private $ms;
    /** @var ApprovedStudentService */
    private $ass;
    
    public function __construct(EntityManager $em, MessageService $ms, ApprovedStudentService $ass)
    {
        $this->em = $em;
        $this->ms = $ms;
        $this->ass = $ass;
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

        if (
            $thread->getCourse()->getLecturer() != $authorID &&
            !$this->ass->isApproved($author, $thread->getCourse())
        ){
            return $this->return403response("Only course lecturer and enrolled students are able to add messages to thread.");
        }

        if ($this->ms->hasMessageInThread($thread, $author))
        {
            return $this->return403response("Already written a message to this thread.");
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
