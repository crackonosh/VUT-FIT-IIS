<?php
namespace App\Services;

use App\Domain\Message;
use Doctrine\ORM\EntityManager;
use App\Domain\Thread;
use App\Domain\User;

class MessageService
{
    public static function addMessage(EntityManager &$em, Thread $thread, User $author, string $message)
    {
        $message = new Message(
            $thread,
            $message,
            $author
        );

        $em->persist($message);
        $em->flush($message);
    }
}