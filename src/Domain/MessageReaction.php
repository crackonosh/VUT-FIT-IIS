<?php
namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MessageReactions")
 */
class MessageReaction
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $reacter;

    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $message;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    public function __construct(
        int $reacter,
        int $message,
        string $name
    ){
        $this->reacter = $reacter;
        $this->message = $message;
        $this->name = $name;
    }
    
    public function getReacter(): int
    {
        return $this->reacter;
    }

    public function getMessage(): int
    {
        return $this->message;
    }

    public function getName(): string
    {
        return $this->name;
    }
}