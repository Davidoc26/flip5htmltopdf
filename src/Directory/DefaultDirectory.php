<?php

namespace Davidoc26\Flip5HtmlToPdf\Directory;

class DefaultDirectory extends Directory
{
    public function __construct(string $dirname)
    {
        $this->fullPath = ROOT . self::SEPARATOR . $dirname . self::SEPARATOR;

        parent::__construct($dirname);
    }
}
