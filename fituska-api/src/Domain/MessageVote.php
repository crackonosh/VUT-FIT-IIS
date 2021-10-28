<?php
namespace App\Domain;

use Doctrine\Common\Cache\VoidCache;
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

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $score;

    public function __construct(
        int $message,
        int $voter,
        bool $score
    ){
        $this->message = $message;
        $this->voter = $voter;
        $this->score = $score;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getVoter(): User
    {
        return $this->voter;
    }

    public function getScore(): bool
    {
        return $this->score;
    }

    public function setScore(bool $score): void
    {
        $this->score = $score;
    }
}