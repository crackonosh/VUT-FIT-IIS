<?php
namespace App\Services;

use DateTime;
use DateTimeZone;
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
            'issued_at' => new DateTime('now', new DateTimeZone('Europe/Prague'))
        );

        return JWT::encode($payload, $this->key);
    }

    public function decodeJWT(string $token): object
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }
}