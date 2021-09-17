<?php

namespace Davidoc26\Flip5HtmlToPdf\Directory;

abstract class Directory
{
    public const SEPARATOR = DIRECTORY_SEPARATOR;

    protected string $dirname;
    protected string $fullPath;

    public function __construct(string $dirname)
    {
        $this->dirname = $dirname;

        if (!is_dir($this->getFullPath())) {
            mkdir($this->getFullPath(), recursive: true);
        }
    }

    /**
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath . self::SEPARATOR;
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }

}
