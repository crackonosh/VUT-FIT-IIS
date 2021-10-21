<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Threads")
 */
class Thread
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="code")
     * @var string
     */
    private $course_code;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $title;

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

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $closed_by;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $closed_on;

    /**
     * @ORM\ManyToOne(targetEntity="ThreadCategory")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $category;
}