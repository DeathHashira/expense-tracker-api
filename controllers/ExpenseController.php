<?php

namespace Controllers;

use App\Http\Request;
use App\Http\Response;
use Middlewares\AuthMiddleware;
use Model\Expenses;

class ExpenseController
{
    public function __construct(
        public Expenses $expensesModel,
        public AuthMiddleware $authMW,
        public Request $request
    ) {}

    public function addExpense(): Response
    {
        $params = $this->request->post();

        $userId = $this->getUserId();
        if (empty($userId)) {
            return (new Response)
            ->setStatusCode(401);
        } else {
            $params["user_id"] = $userId;
        }

        if ($this->expensesModel->create($params)) {
            return (new Response)
            ->setHeader("Location: /expenses");
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    public function deleteExpense(): Response
    {
        $userId = $this->getUserId();
        if (empty($userId)) {
            return (new Response)
            ->setStatusCode(401);
        }

        $id = $this->request->post()['id'];
        if ($this->expensesModel->deleteById($id)) {
            return (new Response)
            ->setHeader("Location: /expenses");
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    public function updateExpense(): Response
    {
        $params = $this->request->post();

        $userId = $this->getUserId();
        if (empty($userId)) {
            return (new Response)
            ->setStatusCode(401);
        }

        $id = $params['id'];
        unset($params['id']);

        if ($this->expensesModel->updateById($id, $params)) {
            return (new Response)
            ->setHeader("Location: /expenses");
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    public function showAllExpenses(): Response
    {
        $params = $this->request->post();

        $userId = $this->getUserId();
        if (empty($userId)) {
            return (new Response)
            ->setStatusCode(401);
        } else {
            $params["user_id"] = $userId;
        }

        $expenses = $this->expensesModel->read($params);
        if (!empty($expenses)) {
            return (new Response)
            ->setHeader("Content-type: application/json")
            ->setContent($expenses);
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }

    private function getUserId(): ?string
    {
        return $this->authMW->handle();
    }
}