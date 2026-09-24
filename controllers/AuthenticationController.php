<?php

namespace Controllers;

use App\Http\Request;
use App\Http\Response;
use Firebase\JWT\JWT;
use Middlewares\AuthMiddleware;
use Model\Users;

class AuthenticationController
{
    public function __construct(
        public Users $usersModel,
        public Request $request
    ) {}

    public function createNewUser(): Response
    {
        $params = $this->request->post();

        $params['password'] = password_hash($params["password"], PASSWORD_DEFAULT);
        
        if ($this->usersModel->create($params)) {
            return (new Response);
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    public function checkLogin(string $key): Response
    {
        $params = $this->request->post();
        $userData = $this->usersModel->read(
            ["email" => $params["email"]]
        )[0];

        if (empty($userData)) {
            return (new Response)
            ->setStatusCode(401);
        } else {
            if (password_verify($params['password'], $userData['password'])) {
                $token = $this->createToken($userData['id'], $key);

                return (new Response)
                ->setHeader("Content-type: application/json")
                ->setContent([
                    "access_token" => $token
                ]);
            } else {
                return (new Response)
                ->setStatusCode(401);
            }
        }
    }

    private function createToken(int $userId, string $key): string
    {
        $payload = [
            "iss" => "hashira-expense-tracker",
            "sub" => $userId,
            "iat" => time(),
            "exp" => time() + 3600
        ];

        return JWT::encode($payload, $key, "HS256");
    }
}