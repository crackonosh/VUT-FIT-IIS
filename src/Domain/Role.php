<?php
namespace Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Roles")
 */
class Role
{
  /**
   * @ORM\ID
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue
   * @var int
   */
  private $ID;

  /**
   * @ORM\Column(type="string", unique=true)
   * @var string
   */
  private $name;
}