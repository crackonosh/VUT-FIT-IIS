<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UserController
{
    public function addUser(Request $request, Response $response): Response
    {
        $response->getBody()->write("kekerino");

        return $response;
    }
}