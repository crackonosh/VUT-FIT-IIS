<?php

function createArgument(string $expectedType, &$argument, bool $isOptional = false): array
{
    return array(
        "expectedType" => $expectedType,
        "value" => $argument,
        "optional" =>$isOptional
    );
}

function parseArgument(string &$error, $arguments): void
{
    foreach ($arguments as $argName => $arg) {
        // continue if expected type is equal to real type
        if ($arg["expectedType"] == gettype($arg["value"])) continue;
        
        // continue if value is NULL and is optional
        if ($arg["value"] == NULL && $arg["optional"]) continue;
        
        $error .= "Argument '$argName' expected types are: '" . $arg["expectedType"] . "', but '" . gettype($arg["value"]) . "' given.\n";
    }
}