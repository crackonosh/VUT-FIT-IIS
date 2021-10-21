<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ApprovedStudents")
 */
class ApprovedStudent
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
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var int
     */
    private $approved_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $approved_on;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="code", nullable=false)
     * @var string
     */
    private $course_code;

    public function __construct(
        int $student,
        string $course_code
    ){
        $this->student = $student;
        $this->course_code = $course_code;
    }
}