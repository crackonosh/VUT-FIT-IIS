<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Domain\User;

class UserService
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function isEmailTaken(string $email): bool
    {
        /** @var User[] */
        $result = $this->em->getRepository(User::class)
            ->findBy(array("email" => "$email"));

        return count($result);
    }
}