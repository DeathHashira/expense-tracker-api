<?php

namespace App\Http;

class Response
{
    private mixed $content;
    private int $statusCode;
    private array $headers;

    public function __construct()
    {
        $this->statusCode = 200;
        $this->headers = [];
        $this->content = null;
    }

    public function setHeader(string $newHeader): self
    {
        $this->headers[] = $newHeader;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setContent(mixed $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function send()
    {
        http_response_code($this->getStatusCode());

        foreach ($this->getHeaders() as $header) {
            header($header);
        }

        echo json_encode($this->getContent());
    }
}