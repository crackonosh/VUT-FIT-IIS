<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;

class UserService
{
    public static function isEmailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isEmailTaken(EntityManager &$em, string $email): bool
    {
        /** @var User[] */
        $result = $em->getRepository(User::class)->findBy(array("email" => "$email"));

        return count($result);
    }
}