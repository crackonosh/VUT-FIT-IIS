<?php
namespace App\Services;

use App\Domain\Message;
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

    public function addMessage(Thread $thread, User $author, string $message): void
    {
        $message = new Message(
            $thread,
            $message,
            $author
        );

        $this->em->persist($message);
        $this->em->flush($message);
    }

    public function hasMessageInThread(Thread $thread, User $author)
    {
        $results = $this->em->getRepository(Message::class)->findBy(
            array("thread" => $thread, "created_by" => $author)
        );

        return count($results);
    }
}