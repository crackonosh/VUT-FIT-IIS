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
    
    public function hashPassword(string $password): string
    {
        return explode(
            '$',
            crypt($password, '$5$rounds=42069$super_secure_salt_you_know_smugZ')
        )[4];
    }
}