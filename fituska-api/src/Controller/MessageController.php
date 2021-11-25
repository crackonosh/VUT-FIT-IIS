<?php
namespace App\Controller;

use App\Domain\Message;
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
            $thread->getCourse()->getLecturer()->getID() != $authorID &&
            !$this->ass->isApproved($author, $thread->getCourse())
        ){
            return $this->return403response("Only course lecturer and enrolled students are able to add messages to thread.");
        }

        if (
            $thread->getClosedBy() != NULL &&
            $thread->getCourse()->getLecturer()->getID() != $authorID
        ){
            return $this->return403response("Only lecturer is able to send message to closed thread.");
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

    public function updateScoreForMessage(Request $request, Response $response, $args): Response
    {
        /** @var Message */
        $message = $this->em->find(Message::class, $args['id']);
        if (!$message)
        {
            return $this->return403response("Unable to update score to not-existing message.");
        }

        $lecturerID = $message->getThread()->getCourse()->getLecturer()->getID();
        $jwtID = $request->getAttribute('jwt')->sub;
        if ($lecturerID != $jwtID)
        {
            return $this->return403response("Only lecturer of course is able to update score for message.");
        }

        $messageAuthor = $message->getCreatedBy();

        $messageAuthor->setScore($this->ms->countVotesForMessage($message));

        $this->em->persist($messageAuthor);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully updated message votes."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function compensateMessages(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "messages" => $this->createArgument("array", $body["messages"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        $msgsCount = count($body['messages']);
        $failed = 0;

        foreach ($body['messages'] as $message)
        {
            /** @var Message */
            $msg = $this->em->find(Message::class, $message['id']);
            if (!$msg) 
            {
                $failed++;
                continue;
            }

            $courseLecturer = $msg->getThread()->getCourse()->getLecturer();
            if ($courseLecturer->getID() != $request->getAttribute('jwt')->sub)
            {
                return $this->return403response("Only lecturer of course is able to compensate messages in it's threads.");
            }

            $msgAuthor = $msg->getCreatedBy();
            $msgAuthor->setScore($message['votes']);
            $this->em->persist($msgAuthor);
        }

        $this->em->flush();
        $succ = $msgsCount - $failed;

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully compensated {$succ} messages from {$msgsCount}."
        )));
        return $response;
    }
}
