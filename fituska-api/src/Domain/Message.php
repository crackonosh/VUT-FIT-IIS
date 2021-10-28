<?php
namespace App\Domain;

use DateTime;
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
     * @var Thread
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
     * @var User
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

    public function getThread(): Thread
    {
        return $this->thread;
    }

    public function getText(): string
    {
        return $this->text;
    }

    // might not be necessary (editing should be allowed?)
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getCreatedBy(): User
    {
        return $this->created_by;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->created_on;
    }
}