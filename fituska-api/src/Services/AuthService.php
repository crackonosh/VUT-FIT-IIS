<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    /** @var string */
    private $key = 'superstrongkey:peepogiggle:';

    public function encodeJWT(int $sub, string $role): string
    {
        $payload = array(
            'iss' => 'http://fituska.org',
            'sub' => $sub,
            'role' => $role,
            'exp' => time() + 1800,
            'issued_at' => time()
        );

        return JWT::encode($payload, $this->key);
    }

    public function decodeJWT(string $token): object
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }
}