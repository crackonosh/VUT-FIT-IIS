<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Domain\User;
use App\Domain\Role;
use App\Services\AuthService;
use App\Services\UserService;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController extends Controller
{
    /** @var UserService */
    private $us;
    /** @var AuthService */
    private $as;

    public function __construct(EntityManager $em, UserService $us, AuthService $as)
    {
        $this->em = $em;
        $this->us = $us;
        $this->as = $as;
    }

    public function loginUser(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "password" => $this->createArgument("string", $body["password"]),
            "email" => $this->createArgument("string", $body["email"]),
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        /** @var User */
        $user = $this->em->getRepository(User::class)->findOneBy(array("email" => "{$body['email']}"));
        
        if (
            !$user||
            $user->getPassword() != $this->us->hashPassword($body['password'])
        ){
            return $this->return403response("Invalid credentials.");
        }

        $jwt = $this->as->encodeJWT($user->getID(), $user->getRole()->getName());

        $response->getBody()->write(json_encode(array(
            "jwt" => $jwt,
            "exp" => time() + 1800,
            "user" => array(
                'id' => $user->getID()
            )
        )));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function addUser(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $bodyArguments = array(
            "name" => $this->createArgument("string", $body["name"]),
            "password" => $this->createArgument("string", $body["password"]),
            "email" => $this->createArgument("string", $body["email"]),
            "address" => $this->createArgument("string", $body["address"], true),
            "phone" => $this->createArgument("string", $body["phone"], true)
        );

        $this->parseArgument($bodyArguments);
        echo($this->errorMsg);

        // check email validity and if it's unique
        if (!$this->us->isEmailValid($body["email"]))
        {
            return $this->return403response("Email is not valid.");
        }
        if ($this->us->isEmailTaken($body["email"]))
        {
            return $this->return403response("Email already exists in database.");
        }

        /** @var Role */
        $userRole = $this->em->find(Role::class, 3);

        if ($userRole == NULL)
        {
            return $this->return403response("Unable to assign user role with not existing ID.");
        }

        $password = $this->us->hashPassword($body['password']);
        
        /** @var User */
        $user = new User(
            $body["name"],
            $password,
            $body["email"],
            $body["address"],
            $body["phone"],
            $userRole
        );

        $this->em->persist($user);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully created new user."
        )));
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus(201);
    }

    public function getUsers(Request $request, Response $response): Response
    {
        if ($request->getAttribute('jwt')->role != 'admin')
        {
            return $this->return403response("Only admin is able to list all users.");
        }

        $results = $this->em->getRepository(User::class)->findAll();
        
        $msg = array();
        /** @var User */
        foreach ($results as $user)
        {
            array_push($msg, $user->__toArray());
        }
        
        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getUser(Request $request, Response $response, $args): Response
    {
        /** @var User */
        $user = $this->em->find(User::class, $args['id']);

        if (!$user)
        {
            $response->getBody()->write("Unable to find user with specified ID.");
            return $response
                ->withHeader('Content-type', 'application/json')
                ->withStatus(404);
        }

        $msg = array(
            "id" => $user->getID(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "phone" => $user->getPhone(),
            "address" => $user->getAddress()
        );

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application-json');
    }

    public function getUsersByEmail(Request $request, Response $response, $args): Response
    {
        $user = $this->em->createQueryBuilder()
            ->select("u")
            ->from(User::class, 'u')
            ->where("u.email LIKE '%" . $args["email"] . "%'");

        $results = $user->getQuery()->getArrayResult();

        $msg = array();
        /** @var User */
        foreach ($results as $result)
        {
            $tmp = array(
                "id" => $result["id"],
                "name" => $result["name"],
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function getUsersByName(Request $request, Response $response, $args): Response
    {
        $user = $this->em->createQueryBuilder()
            ->select("u")
            ->from("App\Domain\User", "u")
            ->where("u.name LIKE '%" . $args["name"] . "%'");

        $results = $user->getQuery()->getArrayResult();

        $msg = array();
        /** @var user */
        foreach ($results as $result)
        {
            $tmp = array(
                "id" => $result["id"],
                "name" => $result['name'],
            );
            array_push($msg, $tmp);
        }

        $response->getBody()->write(json_encode($msg));
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function changeRole(Request $request, Response $response, $args): Response
    {
        $jwtRole = $request->getAttribute('jwt')->role;

        if ($jwtRole != 'admin')
        {
            return $this->return403response("Only user with admin role is able to change other's role.");
        }

        /** @var User */
        $user = $this->em->find(User::class, $args["userID"]);
        /** @var Role */
        $role = $this->em->find(Role::class, $args["roleID"]);

        if (!$user)
        {
            return $this->return403response("Unable to find user with specified ID.");
        }
        if (!$role)
        {
            return $this->return403response("Unable to find role with specified ID.");
        }

        $user->setRole($role);

        $this->em->persist($user);
        $this->em->flush();

        $response->getBody()->write(json_encode(array(
            "message" => "Successfully updated user's role."
        )));

        return $response->withHeader('Content-type', 'application/json');
    }
}