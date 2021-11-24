<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\Role;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RoleController extends Controller
{
    public function __construct(EntityManager $em)
    {
        $this->em = $em;   
    }

    public function addRole(Request $request, Response $response, $args): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'admin')
        {
            return $this->return403response("Only admin is able to read roles.");
        }

        $roleName = $args["name"];

        $role = New Role($roleName);

        $this->em->persist($role);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created new role '$roleName'."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function readRoles(Request $request, Response $response): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'admin')
        {
            return $this->return403response("Only admin is able to read roles.");
        }

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
        return $response
            ->withHeader('Content-type', 'application/json');;
    }

    public function updateRole(Request $request, Response $response, $args): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'admin')
        {
            return $this->return403response("Only admin is able to update roles.");
        }

        $roleID = $args["id"];
        $newName = $args["name"];

        $role = $this->em->find(Role::class, $roleID);
        
        if ($role == NULL)
        {
            return $this->return403response("Unable to find role with specified ID.");
        }

        $role->setName($newName);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Role successfully updated."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function deleteRole(Request $request, Response $response, $args): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'admin')
        {
            return $this->return403response("Only admin is able to read roles.");
        }

        $roleID = $args["id"];

        $role = $this->em->find(Role::class, $roleID);

        if ($role == NULL)
        {
            return $this->return403response("Role with specified ID not found.");
        }

        $this->em->remove($role);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully deleted role."
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }
}