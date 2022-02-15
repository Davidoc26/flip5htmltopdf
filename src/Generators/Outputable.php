<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Generators;

use Symfony\Component\Console\Output\OutputInterface;

interface Outputable
{
    public function setOutput(OutputInterface $output): void;
}
