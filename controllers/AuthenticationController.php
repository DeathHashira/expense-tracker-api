<?php

namespace Controllers;

use App\Http\Request;
use App\Http\Response;
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

        $this->hashPass($params);
        
        if ($this->usersModel->create($params)) {
            return (new Response);
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    private function hashPass(array $data): void
    {
        $data['password'] = password_hash($data["password"], PASSWORD_DEFAULT);
    }

    public function checkLogin(): Response
    {
        $params = $this->request->post();
        $userData = $this->usersModel->read(
            ["email" => $params["email"]]
        );

        if (empty($userData)) {
            return (new Response)
            ->setStatusCode(401);
        } else {
            if ($this->checkPass($params['password'], $userData['password'])) {
                return (new Response);
            } else {
                return (new Response)
                ->setStatusCode(401);
            }
        }
    }

    private function checkPass(string $pass, string $hashedPass): bool
    {
        return password_verify($pass, $hashedPass);
    }
}