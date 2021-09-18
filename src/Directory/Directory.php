<?php

namespace Davidoc26\Flip5HtmlToPdf\Directory;

abstract class Directory
{
    public const SEPARATOR = DIRECTORY_SEPARATOR;

    protected string $path;
    protected string $dirname;

    public function __construct(string $path)
    {
        $path = pathinfo($path);

        $this->path = $path['dirname'];
        $this->dirname = $path['basename'];

        if (!is_dir($this->getFullPath())) {
            mkdir($this->getFullPath(), recursive: true);
        }
    }

    /**
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->path . self::SEPARATOR . $this->dirname . self::SEPARATOR;
    }

    /**
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }
}
