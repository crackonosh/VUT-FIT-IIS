<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Messages")
 */
class Message
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Thread")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $thread;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime
     */
    private $created_on;

    public function __construct(
        int $thread,
        string $text,
        int $created_by
    ){
        $this->thread = $thread;
        $this->text = $text;
        $this->created_by = $created_by;   
    }
}