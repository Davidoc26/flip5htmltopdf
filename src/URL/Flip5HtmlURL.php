<?php

namespace Davidoc26\Flip5HtmlToPdf\URL;

use Stringable;

final class Flip5HtmlURL extends URL implements Stringable
{
    private int $imageNumber = 1;

    public function getImageNumber(): int
    {
        return $this->imageNumber;
    }

    public function incrementImageNumber(): void
    {
        $this->imageNumber++;
    }

    public function __toString(): string
    {
        return $this->scheme . self::SCHEME_SEPARATOR . 'online.fliphtml5.com' . $this->path . '/files/large/' . $this->imageNumber . '.jpg';
    }
}
