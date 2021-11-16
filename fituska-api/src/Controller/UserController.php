<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\User;
use App\Domain\Role;
use App\Services\UserService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController extends Controller
{
    /** @var UserService */
    private $us;

    public function __construct(EntityManager $em, UserService $us)
    {
        $this->em = $em;
        $this->us = $us;
    }

    public function addUser(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();


        $bodyArguments = array(
            "name" => $this->createArgument("string", $body["name"]),
            "password" => $this->createArgument("string", $body["password"]),
            "email" => $this->createArgument("string", $body["email"]),
            "address" => $this->createArgument("string", $body["address"], true),
            "phone" => $this->createArgument("string", $body["phone"], true),
            "role" => $this->createArgument("integer", $body["role"])
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        // check email validity and if it's unique
        if (!$this->us->isEmailValid($body["email"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Email is not valid.");
            return $response;
        }
        if ($this->us->isEmailTaken($body["email"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Email already exists in database.");
            return $response;
        }

        /** @var Role */
        $userRole = $this->em->find(Role::class, $body["role"]);

        if ($userRole == NULL)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to assign user role with not existing ID.");
            return $response;
        }
        
        $user = new User(
            $body["name"],
            $body["password"],
            $body["email"],
            $body["address"],
            $body["phone"],
            $userRole
        );

        $this->em->persist($user);
        $this->em->flush();

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new user.");
        return $response;
        
    }

    public function getUsers(Request $request, Response $response): Response
    {
        $results = $this->em->getRepository(User::class)->findAll();
        
        $msg = array();
        /** @var User */
        foreach ($results as $user)
        {
            array_push($msg, $user->__toArray());
        }
        
        $response->getBody()->write(json_encode($msg));
        return $response;
    }

    public function getUserByEmail(Request $request, Response $response, $args): Response
    {
        $user = $this->em->createQueryBuilder()
            ->select("u")
            ->from(User::class, 'u')
            ->where("u.email LIKE '%" . $args["email"] . "%'");

        $results = $user->getQuery()->getArrayResult();

        if (count($results) == 0)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("No results found.");
            return $response;
        }

        $msg = array();
        /** @var User */
        foreach ($results as $result)
        {
            $tmp = array(
                "id" => $result["id"],
                "name" => $result["name"],
                "email" => $result["email"],
                "phone" => $result["phone"],
                "address" => $result["address"]
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response;
    }

    public function changeRole(Request $request, Response $response, $args): Response
    {
        /** @var User */
        $user = $this->em->find(User::class, $args["userID"]);
        /** @var Role */
        $role = $this->em->find(Role::class, $args["roleID"]);

        if (!$user)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find user with specified ID.");
            return $response;
        }
        if (!$role)
        {
            $response = $response->withStatus(404);
            $response->getBody()->write("Unable to find role with specified ID.");
            return $response;
        }

        $user->setRole($role);

        $this->em->persist($user);
        $this->em->flush();

        $response->getBody()->write("Successfully updated user's role.");
        return $response;
    }
}