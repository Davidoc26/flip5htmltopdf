<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Urls;

final class Builder implements Buildable
{
    /**
     * @param string $url
     * @return Url
     */
    public static function build(string $url): Url
    {
        return new Url(UrlParser::fromUrl($url)->toMainType());
    }
}
