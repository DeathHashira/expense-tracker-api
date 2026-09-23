<?php

use Dotenv\Dotenv;
use App\Http\Request;
use Controllers\AuthenticationController;
use Controllers\ExpenseController;
use Model\Database;
use Model\Expenses;
use Model\Users;
use Src\Router;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

$request = Request::createFromGlobal();
$conn = Database::createFromEnv()->getConnection();

$authController = new AuthenticationController(
    new Users($conn),
    $request
);

$expenController = new ExpenseController(
    new Expenses($conn),
    $request
);

Router::post("/signup", function() use ($authController) {
    $response = $authController->createNewUser();
    $response->send();
});

Router::post("/login", function() use ($authController) {
    $response = $authController->checkLogin();
    $response->send();
});

Router::post("/expenses/add", function() use ($expenController) {
    $response = $expenController->addExpense();
    $response->send();
});

Router::post("/expenses/delete", function() use ($expenController) {
    $response = $expenController->deleteExpense();
    $response->send();
});

Router::post("/expenses/update", function() use ($expenController) {
    $response = $expenController->updateExpense();
    $response->send();
});

Router::post("/expenses", function() use ($expenController) {
    $response = $expenController->showAllExpenses();
    $response->send();
});

Router::run();