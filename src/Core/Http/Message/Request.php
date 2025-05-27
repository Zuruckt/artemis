<?php

namespace App\Core\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    private array $headers = [];

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        // TODO: Implement getProtocolVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        // TODO: Implement withProtocolVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        // TODO: Implement hasHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        // TODO: Implement getHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withAddedHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        // TODO: Implement withoutHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        // TODO: Implement getBody() method.
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        // TODO: Implement withBody() method.
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        // TODO: Implement getRequestTarget() method.
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        // TODO: Implement withRequestTarget() method.
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        // TODO: Implement getMethod() method.
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        // TODO: Implement withMethod() method.
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        // TODO: Implement getUri() method.
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        // TODO: Implement withUri() method.
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        // TODO: Implement getServerParams() method.
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        // TODO: Implement getCookieParams() method.
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        // TODO: Implement withCookieParams() method.
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        // TODO: Implement getQueryParams() method.
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        // TODO: Implement withQueryParams() method.
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        // TODO: Implement getUploadedFiles() method.
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        // TODO: Implement withUploadedFiles() method.
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        // TODO: Implement getParsedBody() method.
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        // TODO: Implement withParsedBody() method.
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name, $default = null)
    {
        // TODO: Implement getAttribute() method.
    }

    /**
     * @inheritDoc
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        // TODO: Implement withAttribute() method.
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        // TODO: Implement withoutAttribute() method.
    }
}