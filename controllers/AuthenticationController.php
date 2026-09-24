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

        $params['password'] = password_hash($params["password"], PASSWORD_DEFAULT);
        
        if ($this->usersModel->create($params)) {
            return (new Response);
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    public function checkLogin(): Response
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
                return (new Response);
            } else {
                return (new Response)
                ->setStatusCode(401);
            }
        }
    }
}