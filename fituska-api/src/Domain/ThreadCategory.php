<?php
namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ThreadCategories")
 */
class ThreadCategory
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var User
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="code", nullable=false)
     * @var Course
     */
    private $course_code;

    public function __construct(
        string $name,
        int $created_by,
        string $course_code
    ){
        $this->name = $name;
        $this->created_by = $created_by;
        $this->course_code = $course_code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedBy(): User
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function getCourseCode(): Course
    {
        return $this->course_code;
    }

    public function setCourseCode(int $course_code): void
    {
        $this->course_code = $course_code;
    }
}