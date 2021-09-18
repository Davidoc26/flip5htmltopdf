<?php

namespace Davidoc26\Flip5HtmlToPdf;

use Davidoc26\Flip5HtmlToPdf\Directory\DefaultDirectory;
use Davidoc26\Flip5HtmlToPdf\Directory\TempDirectory;
use Davidoc26\Flip5HtmlToPdf\URL\Flip5HtmlURL;
use FPDF;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Flip5HtmlCLI extends Parser implements Outputable
{
    public const ALLOWED_URLS = [
        'https://fliphtml5.com/',
        'https://online.fliphtml5.com/',
    ];

    private OutputInterface $output;
    private string $outputFilename;
    private bool $dropTemp;

    /**
     * @param string $url
     * @param string $outputFilename
     * @param bool $dropTemp
     */
    public function __construct(string $url, string $outputFilename, bool $dropTemp = true)
    {
        parent::__construct($url);
        $this->url = $url;
        $this->outputFilename = $outputFilename;
        $this->dropTemp = !$dropTemp;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function fetchPages(Flip5HtmlURL $url): void
    {
        $tempDir = new TempDirectory(ROOT . '/temp', $this->dropTemp);
        $pdf = new FPDF();
        $pdf->SetAutoPageBreak(false);

        $this->getPagesToPdf($url, $pdf, $tempDir, $this->output);

        $outputDir = new DefaultDirectory(ROOT . '/output');

        $pdf->Output('F', $outputDir->getFullPath() . $this->outputFilename . '.pdf');
    }

}
