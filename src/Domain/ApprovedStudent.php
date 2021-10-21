<?php
namespace Domain;

use DateTime;
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

    public function getStudent(): int
    {
        return $this->student;
    }

    public function getCourseCode(): string
    {
        return $this->course_code;
    }

    public function getApprovedBy(): int
    {
        return $this->approved_by;
    }

    public function setApprovedBy(int $approved_by): void
    {
        $this->approved_by = $approved_by;
    }

    public function getApprovedOn(): DateTime
    {
        return $this->approved_on;
    }

    public function setApprovedOn(DateTime $approved_on): void
    {
        $this->approved_on = $approved_on;
    }
}