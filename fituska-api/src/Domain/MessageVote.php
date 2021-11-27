<?php
namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MessageVotes")
 */
class MessageVote
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var Message
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var User
     */
    private $voter;

    public function __construct(
        Message $message,
        User $voter
    ){
        $this->message = $message;
        $this->voter = $voter;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getVoter(): User
    {
        return $this->voter;
    }
}