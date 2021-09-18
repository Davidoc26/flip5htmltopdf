<?php

namespace Davidoc26\Flip5HtmlToPdf;

use Symfony\Component\Console\Output\OutputInterface;

interface Outputable
{
    public function setOutput(OutputInterface $output): void;
}