<?php

namespace Davidoc26\Flip5HtmlToPdf\URL;

use Stringable;

class URL implements Stringable
{
    public const SCHEME_SEPARATOR = '://';
    public const URL_SEPARATOR = '/';

    protected ?string $scheme;
    protected ?string $host;
    protected ?string $path;

    /**
     * @param array $parsedUrl
     * @return static
     */
    public static function create(array $parsedUrl): static
    {
        $url = new static();

        $url->setScheme($parsedUrl['scheme'] ?? null);
        $url->setHost($parsedUrl['host'] ?? null);
        $url->setPath($parsedUrl['path'] ?? null);

        return $url;
    }

    /**
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $scheme
     */
    public function setScheme(?string $scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * @param string|null $host
     */
    public function setHost(?string $host): void
    {
        $this->host = $host;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getWithoutPath(): string
    {
        return $this->scheme . self::SCHEME_SEPARATOR . $this->host . self::URL_SEPARATOR;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->scheme . self::SCHEME_SEPARATOR . $this->host . $this->path;
    }
}
