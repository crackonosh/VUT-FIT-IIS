<?php
namespace Domain;

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
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $reacter;

    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $message;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $name;
}