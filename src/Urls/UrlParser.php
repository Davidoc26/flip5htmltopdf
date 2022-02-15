<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Urls;

use Davidoc26\Flip5HtmlToPdf\Exceptions\IncorrectURLException;
use function parse_url;
use function str_ends_with;
use function str_replace;
use function str_starts_with;

final class UrlParser
{
    public const SCHEME_SEPARATOR = '://';

    private function __construct(
        private ?string $scheme,
        private ?string $host,
        private ?string $path
    )
    {
    }

    /**
     * @param string $url
     * @return self
     */
    public static function fromUrl(string $url): self
    {
        $parsed = parse_url($url);

        return new self($parsed['scheme'], $parsed['host'], $parsed['path']);
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
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function toMainType(): string
    {
        $host = $this->removeOnlineHost();

        return $this->scheme . self::SCHEME_SEPARATOR . $host . (str_ends_with($this->path, '/') ? $this->path : $this->path . '/');
    }

    /**
     * @throws IncorrectURLException
     */
    private function removeOnlineHost(): string
    {
        $replaced = str_replace('online.', '', $this->host);
        if (!str_starts_with($replaced, 'flip')) {
            throw new IncorrectURLException("URL must be compatible with https://fliphtml5.com/ or https://online.fliphtml5.com/ ");
        }

        return $replaced;
    }
}
