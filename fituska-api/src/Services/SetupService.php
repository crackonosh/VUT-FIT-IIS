<?php
namespace App\Services;

use App\Domain\Role;
use App\Domain\User;
use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class SetupService
{
    /** @var EntityManager */
    private $em;
    /** @var UserService */
    private $us;

    public function __construct(EntityManager $em, UserService $us)
    {
        $this->em = $em;
        $this->us = $us;
    }

    public function setup(Request $request, Response $response): Response
    {
        $roles = ['admin', 'moderator', 'user'];
        foreach ($roles as $role)
        {
            $tmp = new Role($role);
            $this->em->persist($tmp);
        }
        $this->em->flush();

        $admin = new User(
            "admin",
            $this->us->hashPassword("admin"),
            "admin@admin.com",
            null,
            null,
            $this->em->find(Role::class, 1)
        );

        $this->em->persist($admin);
        $this->em->flush();

        return $response;
    }
}