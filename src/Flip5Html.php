<?php

namespace Davidoc26\Flip5HtmlToPdf;

use Davidoc26\Flip5HtmlToPdf\Directory\DefaultDirectory;
use Davidoc26\Flip5HtmlToPdf\Directory\TempDirectory;
use Davidoc26\Flip5HtmlToPdf\URL\Flip5HtmlURL;
use FPDF;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Flip5Html extends Parser
{
    public const ALLOWED_URLS = [
        'https://fliphtml5.com/',
        'https://online.fliphtml5.com/',
    ];

    private string $outputFilename;
    private string $outputPath;
    private string $tempPath;
    private bool $dropTemp;

    /**
     * @param string $url
     * @param string $outputPath
     * @param string $outputFilename
     * @param string $tempPath
     * @param bool $dropTemp
     */
    public function __construct(string $url, string $outputPath, string $outputFilename, string $tempPath, bool $dropTemp = true)
    {
        parent::__construct($url);
        $this->url = $url;
        $this->outputPath = $outputPath;
        $this->outputFilename = $outputFilename;
        $this->tempPath = $tempPath;
        $this->dropTemp = $dropTemp;
    }

    /**
     * @param Flip5HtmlURL $url
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function fetchPages(Flip5HtmlURL $url): void
    {
        $tempDir = new TempDirectory($this->tempPath, $this->dropTemp);
        $pdf = new FPDF();
        $pdf->SetAutoPageBreak(false);

        $this->getPagesToPdf($url, $pdf, $tempDir, null);

        $outputDir = new DefaultDirectory($this->outputPath);

        $pdf->Output('F', $outputDir->getFullPath() . $this->outputFilename . '.pdf');
    }

}
