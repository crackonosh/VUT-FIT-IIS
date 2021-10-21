<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Courses")
 */
class Course
{
    /**
     * @ORM\ID
     * @ORM\Column(type="string", length=12)
     * @var int
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $approved_by;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     * @var DateTime
     */
    private $created_on;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $approved_on;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     * @var bool
     */
    private $is_approved;
}