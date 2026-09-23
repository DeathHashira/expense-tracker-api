<?php

namespace Src;

class Router
{
    private static array $routes = [
        "get" => [],
        "post" => []
    ];

    private static function getMethod(): string
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    private static function getPath(): string
    {
        return parse_url($_SERVER["REQUEST_URI"])['path'];
    }

    public static function get(string $path, callable $func): void
    {
        Router::$routes['get'][$path] = $func;
    }

    public static function post(string $path, callable $func): void
    {
        Router::$routes['post'][$path] = $func;
    }

    public static function run(): void
    {
        $method = Router::getMethod();
        $path = Router::getPath();

        $func = Router::$routes[$method][$path] ?? null;

        if (is_callable($func)) {
            $func();
        }
    }
}