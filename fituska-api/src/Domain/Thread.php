<?php
namespace App\Domain;

use DateTime;
use DateTimeZone;
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
     * @var Course
     */
    private $course;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $title;

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

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var User
     */
    private $closed_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $closed_on;

    /**
     * @ORM\ManyToOne(targetEntity="ThreadCategory")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=true)
     * @var ?ThreadCategory
     */
    private $category;

    public function __construct(
        Course $course,
        string $title,
        User $created_by,
        ThreadCategory $category = NULL
    ){
        $this->course= $course;
        $this->title = $title;
        $this->created_by = $created_by;
        $this->category = $category;
        $this->created_on = new DateTime('now', new DateTimeZone("Europe/Prague"));
    }

    public function getCourseCode(): string
    {
        return $this->course_code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCreatedBy(): User
    {
        return $this->created_by;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->created_on;
    }

    public function getClosedBy(): ?User
    {
        return $this->closed_by;
    }

    public function setClosedBy(int $closed_by): void
    {
        $this->closed_by = $closed_by;
    }

    public function getClosedOn(): ?DateTime
    {
        return $this->closed_on;
    }

    public function setClosedOn(DateTime $closed_on): void
    {
        $this->closed_on = $closed_on;
    }

    public function getCategory(): ?ThreadCategory
    {
        return $this->category;
    }

    public function setCategory(ThreadCategory $category): void
    {
        $this->category = $category;
    }
}