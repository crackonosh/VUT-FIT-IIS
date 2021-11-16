<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\Role;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class RoleController
{
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

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new role '$roleName'.");
        return $response;
    }

    public function readRoles(Request $request, Response $response): Response
    {
        $roles = $this->em->getRepository(Role::class)->findBy(array(), array("id" => "asc"));

        $msg = array();
        /** @var Role */
        foreach ($roles as $role)
        {
            $tmp = array(
                "id" => $role->getID(),
                "name" => $role->getName()
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response;
    }

    public function updateRole(Request $request, Response $response, $args): Response
    {
        $roleID = $args["id"];
        $newName = $args["name"];

        $role = $this->em->find(Role::class, $roleID);
        
        if ($role == NULL)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find role with specified ID.");
            return $response;
        }

        $role->setName($newName);
        $this->em->flush();

        $response->getBody()->write("Role successfully updated.");
        return $response;
    }

    public function deleteRole(Request $request, Response $response, $args): Response
    {
        $roleID = $args["id"];

        $role = $this->em->find(Role::class, $roleID);

        if ($role == NULL)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Role with specified ID not found.");
            return $response;
        }

        $this->em->remove($role);
        $this->em->flush();

        $response->getBody()->write("Successfully deleted role.");
        return $response;
    }
}