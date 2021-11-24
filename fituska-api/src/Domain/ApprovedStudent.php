<?php
namespace App\Domain;

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
     * @var User
     */
    private $student;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var User
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
     * @var Course
     */
    private $course;

    public function __construct(
        User $student,
        Course $course
    ){
        $this->student = $student;
        $this->course = $course;
        $this->status = false;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approved_by;
    }

    public function setApprovedBy(User $approved_by): void
    {
        $this->approved_by = $approved_by;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function getApprovedOn(): ?DateTime
    {
        return $this->approved_on;
    }

    public function setApprovedOn(DateTime $approved_on): void
    {
        $this->approved_on = $approved_on;
    }
}