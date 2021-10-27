<?php
namespace App\Domain;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Courses")
 */
class Course
{
    /**
     * @ORM\ID
     * @ORM\Column(type="string", length=16)
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
     * @var User
     */
    private $lecturer;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @var User
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
        User $lecturer
    ){
        $this->code = $code;
        $this->name = $name;
        $this->lecturer = $lecturer;
        $this->created_on = new DateTime('now', new DateTimeZone("Europe/Prague"));
    }

    public function __toArray(): array
    {
        return array(
            "code" => $this->code,
            "name" => $this->name,
            "lecturer" => $this->lecturer,
            "approved_by" => $this->approved_by,
            "created_on" => $this->created_on,
            "approved_on" => $this->approved_on
        );
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

    public function getLecturer(): User
    {
        return $this->lecturer;
    }

    public function getCreatedOn(): DateTime
    {
        return $this->created_on;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approved_by;
    }

    public function setApprovedBy(int $approved_by): void
    {
        $this->approved_by = $approved_by;
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