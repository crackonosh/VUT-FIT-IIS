<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MessageAttachments")
 */
class MessageAttachment
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $message;

    public function __construct(
        string $name,
        int $message
    ){
        $this->name = $name;
        $this->message = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessage(): int
    {
        return $this->message;
    }
}