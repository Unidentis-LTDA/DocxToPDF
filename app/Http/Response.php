<?php

namespace App\Http;

class Response
{
    private mixed $content;
    private int $httpCode;
    private string $contentType;
    private array $headers = [];

    public function __construct($content, $httpCode = 200, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): void
    {
        $this->httpCode = $httpCode;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeader($key, $value): void
    {
        $this->headers[$key] = $value;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function sendHeaders(): void
    {
        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value){
            header(sprintf("%s:%s", $key, $value));
        }
    }

    public function sendResponse(): void
    {
        $this->sendHeaders();
        switch ($this->contentType){
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
}
