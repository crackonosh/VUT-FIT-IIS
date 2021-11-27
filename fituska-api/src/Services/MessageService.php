<?php
namespace App\Services;

use App\Domain\Message;
use App\Domain\MessageVote;
use App\Domain\Thread;
use App\Domain\User;
use Doctrine\ORM\EntityManager;

class MessageService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addMessage(Thread $thread, User $author, string $message): Message
    {
        $message = new Message(
            $thread,
            $message,
            $author
        );

        $this->em->persist($message);
        $this->em->flush($message);

        return $message;
    }

    public function hasMessageInThread(Thread $thread, User $author)
    {
        $results = $this->em->getRepository(Message::class)->findBy(
            array("thread" => $thread, "created_by" => $author)
        );

        return count($results);
    }
    
    public function countVotesForMessage(Message $message): int
    {
        return count($this->em->getRepository(MessageVote::class)
            ->findBy(array('message' => $message->getID())));
    }

    public function votedForMessage(Message $message, User $voter): bool
    {
        return count($this->em->getRepository(MessageVote::class)
            ->findBy(array(
                'message' => $message->getID(),
                'voter' => $voter->getID()
            )));
    }

    /**
     * @param array $attachments containing key-value pairs for file type and file content
     * @return array containing filenames of processed files
     */
    public function processAttachments(array $attachments): array
    {
        $tmp = array();
        foreach($attachments as $a)
        {
            $filename = hash('sha256', $a['content']) . ".{$a['type']}";
            $file = base64_decode($a['content']);
            $path = __DIR__ . "/../public/images/";

            if (file_put_contents($path . $filename, $file))
            {
                array_push($tmp, $filename);
            }
        }
        return $tmp;
    }
}