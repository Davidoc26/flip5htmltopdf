<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Urls;

interface Incremental
{
    public function increment(): void;

    public function getIncrementedUrl(): string;
}
