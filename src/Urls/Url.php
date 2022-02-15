<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Urls;

class Url implements Incremental
{
    private UrlParser $parsed;
    public string $url;
    private int $imageNumber = 1;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->parsed = UrlParser::fromUrl($url);
    }

    public function getImageNumber(): int
    {
        return $this->imageNumber;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function increment(): void
    {
        $this->imageNumber++;
    }

    public function getIncrementedUrl(): string
    {
        return $this->parsed->getScheme() . UrlParser::SCHEME_SEPARATOR . 'online.fliphtml5.com' . $this->parsed->getPath() . 'files/large/' . $this->imageNumber . '.jpg';
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
