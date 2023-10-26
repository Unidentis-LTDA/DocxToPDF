<?php

namespace App\Http;

class Request
{
    private string $httpMethod;

    private string $uri;
    private array $queryParams;
    private array $postVars;

    private array $headers;
    private Router $router;

    public function __construct($router)
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders() ?? [];
        $this->router = $router;
    }


    public function getHttpMethod(): mixed
    {
        return $this->httpMethod;
    }


    public function setHttpMethod(mixed $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }


    public function getUri(): mixed
    {
        return $this->uri;
    }


    public function setUri(mixed $uri): void
    {
        $this->uri = $uri;
    }


    public function getQueryParams(): array
    {
        return $this->queryParams;
    }


    public function setQueryParams(array $queryParams): void
    {
        $this->queryParams = $queryParams;
    }


    public function getPostVars(): array
    {
        return $this->postVars;
    }


    public function setPostVars(array $postVars): void
    {
        $this->postVars = $postVars;
    }


    public function getHeaders(): false|array
    {
        return $this->headers;
    }


    public function setHeaders(false|array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }
}
