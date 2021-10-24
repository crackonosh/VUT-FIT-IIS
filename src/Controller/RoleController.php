<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\Role;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class RoleController
{
    private $em;
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addRole(Request $request, Response $response, $args): Response
    {
        $roleName = $args["name"];

        $role = New Role($roleName);

        $this->em->persist($role);
        $this->em->flush();

        $response->withStatus(201);
        $response->getBody()->write("Successfully created new role '$roleName'.");
        return $response;
    }
}