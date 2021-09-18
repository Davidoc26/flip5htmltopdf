<?php

namespace Davidoc26\Flip5HtmlToPdf\Directory;

use DateTimeImmutable;
use function rmdir;
use function glob;
use function unlink;

class TempDirectory extends Directory
{
    protected bool $isRemovable = true;

    public function __construct(string $path, bool $isRemovable = true)
    {
        $path .= self::SEPARATOR . (new DateTimeImmutable('now'))->format('d-m-Y_i:s');

        parent::__construct($path);
        $this->isRemovable = $isRemovable;
    }

    private function unlinkFiles(): void
    {
        foreach (glob($this->getFullPath() . "*") as $item) {
            unlink($item);
        }
    }

    public function __destruct()
    {
        if ($this->isRemovable) {
            $this->unlinkFiles();
            rmdir($this->getFullPath());
        }
    }

}
