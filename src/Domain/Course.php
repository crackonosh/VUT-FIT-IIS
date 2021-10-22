<?php
namespace App\Domain;

use DateTime;
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
     * @var string
     */
    private $code;

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
    private $lecturer;

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

    public function __construct(
        string $code,
        string $name,
        int $lecturer
    ){
        $this->code = $code;
        $this->name = $name;
        $this->lecturer = $lecturer;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLecturer(): int
    {
        return $this->lecturer;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->created_on;
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