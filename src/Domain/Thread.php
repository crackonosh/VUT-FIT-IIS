<?php
namespace Domain;

use DateTime;
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
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="code", nullable=false)
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
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $category;

    public function __construct(
        string $course_code,
        string $title,
        int $created_by,
        int $category
    ){
        $this->course_code = $course_code;
        $this->title = $title;
        $this->created_by = $created_by;
        $this->category = $category;
    }

    public function getCourseCode(): string
    {
        return $this->course_code;
    }

    public function setCourseCode(string $course_code): void
    {
        $this->course_code = $course_code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCreatedBy(): int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->created_on;
    }

    public function getClosedBy(): int
    {
        return $this->closed_by;
    }

    public function setClosedBy(int $closed_by): void
    {
        $this->closed_by = $closed_by;
    }

    public function getClosedOn(): DateTime
    {
        return $this->closed_on;
    }

    public function setClosedOn(DateTime $closed_on): void
    {
        $this->closed_on = $closed_on;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }
}