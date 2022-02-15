<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Generators;

use Davidoc26\Flip5HtmlToPdf\Directory\DefaultDirectory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PdfGeneratorCLI extends Generator implements Outputable
{
    public function __construct(string $outputPath, protected string $outputName, protected OutputInterface $output)
    {
        parent::__construct($outputPath);
    }

    public function generate(array $responses): void
    {
        $this->pdf->SetAutoPageBreak(false);
        $dir = new DefaultDirectory($this->outputPath);

        /**
         * @var ResponseInterface[] $responses
         */
        foreach ($responses as $k => $response) {
            $this->output->writeln("<info>Generating page " . $k + 1 . " from {$response->getInfo()['url']}</info>");

            $this->pdf->AddPage();
            $this->pdf->Image('@' . $response->getContent(), 0, 0, w: 210, h: 297);
        }

        $outputPathWithName = $dir->getFullPath() . $this->outputName . '.pdf';
        $this->pdf->Output($outputPathWithName, 'F');

        $this->output->writeln("<fg=bright-yellow>Successfully generated in <info>$outputPathWithName</info></>");
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }
}
