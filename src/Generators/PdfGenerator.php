<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Generators;

use Davidoc26\Flip5HtmlToPdf\Directory\DefaultDirectory;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PdfGenerator extends Generator
{
    public function __construct(string $outputPath, protected string $outputName)
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
        foreach ($responses as $response) {
            $this->pdf->AddPage();
            $this->pdf->Image('@' . $response->getContent(), 0, 0, w: 210, h: 297);
        }

        $this->pdf->Output($dir->getFullPath() . $this->outputName . '.pdf', 'F');
    }
}
