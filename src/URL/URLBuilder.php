<?php

namespace Davidoc26\Flip5HtmlToPdf\URL;

class URLBuilder
{
    /**
     * @param string $url
     * @param string $type
     * @return URL|Flip5HtmlURL
     */
    public static function build(string $url, string $type = URL::class): URL|Flip5HtmlURL
    {
        $parsedUrl = parse_url($url);

        return match ($type) {
            Flip5HtmlURL::class => Flip5HtmlURL::create($parsedUrl),
            default => URL::create($parsedUrl),
        };
    }
}
