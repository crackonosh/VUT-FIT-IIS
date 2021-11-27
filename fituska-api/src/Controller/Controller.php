<?php
namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Slim\Psr7\Response;

abstract class Controller
{
    /** @var EntityManager */
    protected $em;

    /** @var string */
    protected $errorMsg = "";

    protected function createArgument(string $expectedType, &$argument, bool $isOptional = false): array
    {
        return array(
            "expectedType" => $expectedType,
            "value" => $argument,
            "optional" =>$isOptional
        );
    }

    protected function parseArgument($arguments): void
    {
        foreach ($arguments as $argName => $arg) {
            // continue if expected type is equal to real type
            if ($arg["expectedType"] == gettype($arg["value"])) continue;
            
            // continue if value is NULL and is optional
            if ($arg["value"] == NULL && $arg["optional"]) continue;
            
            $this->errorMsg .= "Argument '$argName' expected types are: '" . $arg["expectedType"] . "', but '" . gettype($arg["value"]) . "' given.\n";
        }
    }

    protected function return403response(string $msg): Response
    {
        $response = new Response(403);

        $response->getBody()->write(json_encode(array(
            "message" => $msg
        )));

        return $response->withHeader('Content-type', 'application/json');
    }
}