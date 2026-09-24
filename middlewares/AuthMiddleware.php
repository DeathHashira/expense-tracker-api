<?php

namespace Middlewares;

use App\Http\Request;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public function __construct(
        private Request $request,
        private string $key
    ) {}

    private function parseSub(object $tokenObject): string
    {
        return $tokenObject->sub;
    }

    private function validate(string $token): object
    {
        return JWT::decode($token, new Key($this->key, "HS256"));
    }

    public function handle(): ?string
    {
        $token = $this->getToken();
        if (empty($token)) {
            return null;
        } else {
            try {
                $sub = $this->parseSub($this->validate($token));
                return $sub;
            } catch(Exception $e) {
                return null;
            }
        }
    }

    private function getToken(): string
    {
        $authorization = $this->request->getAuthHeader();
        return explode(" ", $authorization)[1] ?? '';
    }
}