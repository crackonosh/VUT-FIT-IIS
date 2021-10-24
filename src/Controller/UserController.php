<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Domain\User;
use App\Domain\Role;
use App\Service\UserService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../Functions.php';

class UserController
{
    private $em;
    private $errorMsg = "";

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addUser(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();


        $bodyArguments = array(
            "name" => createArgument("string", $body["name"]),
            "password" => createArgument("string", $body["password"]),
            "email" => createArgument("string", $body["email"]),
            "address" => createArgument("string", $body["address"], true),
            "phone" => createArgument("string", $body["phone"], true),
            "role" => createArgument("integer", $body["role"])
        );

        parseArgument($this->errorMsg, $bodyArguments);
        echo($this->errorMsg);

        // check email validity and if it's unique
        if (!UserService::isEmailValid($body["email"]))
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Email is already taken.");
            return $response;
        }

        /** @var Role */
        $userRole = $this->em->getRepository("App\Domain\Role")->find($body["role"]);

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

        try {
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e)
        {
            $response = $response->withStatus(403);
            $response->getBody()->write("Email already exists in database.");
            return $response;
        }

        $response = $response->withStatus(201);
        $response->getBody()->write("Successfully created new user.");
        return $response;
        
    }

    public function getUsers(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode($this->em->getRepository("App\Domain\User")->findAll()));
        return $response;
    }
}