<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private $secret_key = "tose@1998";

    public function generateToken($user_id)
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60),
            'user_id' => $user_id
        ];

        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    public function verifyToken($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            return [true, $decoded]; // Contient les données du token
        } catch (Exception $e) {
            return ['error' => 'Token invalide : ' . $e->getMessage()];
        }
    }
}
