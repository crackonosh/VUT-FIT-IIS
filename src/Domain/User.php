<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class User
{
    /**
     * @ORM\ID
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=512)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=13, nullable= true)
     * @var string
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var int
     */
    private $role;
}