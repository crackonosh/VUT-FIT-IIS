<?php
namespace Domain;

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
     * @var int
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="code", nullable=false)
     * @var string
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
}