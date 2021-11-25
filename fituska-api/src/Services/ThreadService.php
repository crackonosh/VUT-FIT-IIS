<?php
namespace App\Services;

use App\Domain\Message;
use App\Domain\MessageAttachment;

class ThreadService
{
    /**
     * @param Message[] $msgs
     */
    public function prepareMessagesForResponse($msgs)
    {
        $response = array();

        /** @var Message */
        foreach ($msgs as $msg)
        {
            $role = $msg->getThread()->getCourse()->getLecturer()->getID() == $msg->getCreatedBy()->getID() ? 'lecturer' : 'student';

            $attachments = array();
            /** @var MessageAttachment */
            foreach ($msg->getAttachments() as $a)
            {
                array_push($attachments, $a->getName());
            }

            $tmp = array(
                'text' => $msg->getText(),
                'role' => $role,
                'author' => array(
                    'id' => $msg->getCreatedBy()->getID(),
                    'name' => $msg->getCreatedBy()->getName()
                ),
                'created_on' => $msg->getCreatedOn(),
                'attachments' => $attachments
            );
            array_push($response, $tmp);
        }
        
        return $response;
    }
}