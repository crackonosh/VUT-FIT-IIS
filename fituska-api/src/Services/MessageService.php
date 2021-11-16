<?php
namespace App\Services;

use App\Domain\Message;
use Doctrine\ORM\EntityManager;
use App\Domain\Thread;
use App\Domain\User;

class MessageService
{
    /** @var EntityManage */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addMessage(Thread $thread, User $author, string $message)
    {
        $message = new Message(
            $thread,
            $message,
            $author
        );

        $this->em->persist($message);
        $this->em->flush($message);
    }
}