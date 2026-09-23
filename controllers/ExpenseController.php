<?php

namespace Controllers;

use App\Http\Request;
use App\Http\Response;
use Model\Expenses;

class ExpenseController
{
    public function __construct(
        public Expenses $expensesModel,
        public Request $request
    ) {}

    public function addExpense(): Response
    {
        $params = $this->request->post();

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

        $expenses = $this->expensesModel->read($params);
        if (!empty($expenses)) {
            return (new Response)
            ->setContent($expenses);
        } else {
            return (new Response)
            ->setStatusCode(400);
        }
    }
}