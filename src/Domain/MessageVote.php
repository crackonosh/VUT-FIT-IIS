<?php
namespace Domain;

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
     * @var int
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $voter;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
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
}