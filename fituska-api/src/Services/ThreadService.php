<?php
namespace App\Services;

use App\Domain\Message;

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
            $tmp = array(
                'text' => $msg->getText(),
                'role' => $role,
                'author' => array(
                    'id' => $msg->getCreatedBy()->getID(),
                    'name' => $msg->getCreatedBy()->getName()
                ),
                'created_on' => $msg->getCreatedOn()
            );
            array_push($response, $tmp);
        }
        
        return $response;
    }
}