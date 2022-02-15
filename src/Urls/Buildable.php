<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Urls;

interface Buildable
{
    public static function build(string $url);
}
