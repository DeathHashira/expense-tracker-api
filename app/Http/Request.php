<?php

namespace App\Http;

class Request
{
    private string $method;
    private string $uri;
    private array $context = [];

    public function __construct(
        private array $getParams,
        private array $postParams,
        private array $server
    )
    {
        $this->method = strtolower($this->server["REQUEST_METHOD"]);
        $this->uri = parse_url($this->server["REQUEST_URI"])['path'];
    }

    public static function createFromGlobal(): self
    {
        return new self(
            $_GET,
            $_POST,
            $_SERVER
        );
    }

    public function getContext(string $key, mixed $default=null): mixed
    {
        return $this->context[$key] ?? $default;
    }

    public function getAuthHeader(): string
    {
        return getallheaders()["Authorization"];
    }

    public function setContext(string $key, mixed $value): self
    {
        $this->context[$key] = $value;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function isPost(): bool
    {
        if ($this->method === "post") {
            return true;
        } else {
            return false;
        }
    }

    public function get(mixed $default=null): array
    {
        return $this->getParams ?? $default;
    }

    public function post(mixed $default=null): array
    {
        return $this->postParams ?? $default;
    }
}