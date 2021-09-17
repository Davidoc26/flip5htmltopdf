<?php

namespace Davidoc26\Flip5HtmlToPdf\Parser;

use Davidoc26\Flip5HtmlToPdf\Directory\DefaultDirectory;
use Davidoc26\Flip5HtmlToPdf\Directory\TempDirectory;
use Davidoc26\Flip5HtmlToPdf\Exception\BookNotFoundException;
use Davidoc26\Flip5HtmlToPdf\URL\Flip5HtmlURL;
use Davidoc26\Flip5HtmlToPdf\URL\URLBuilder;
use FPDF;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function ob_start;
use function ob_get_clean;
use function str_contains;
use function imagecreatefromstring;
use function imagejpeg;
use function file_put_contents;

class Flip5Html
{
    public const ALLOWED_URLS = [
        'https://fliphtml5.com/',
        'https://online.fliphtml5.com/',
    ];

    private HttpClientInterface $client;
    private string $url;
    private string $outputFilename;
    private bool $dropTemp;

    /**
     * @param string $url
     * @param string $outputFilename
     * @param bool $dropTemp
     */
    public function __construct(string $url, string $outputFilename, bool $dropTemp)
    {
        $this->client = HttpClient::create();
        $this->url = $url;
        $this->outputFilename = $outputFilename;
        $this->dropTemp = !$dropTemp;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchPage(Flip5HtmlURL $url)
    {
        $directory = new TempDirectory('temp', $this->dropTemp);

        $pdf = new FPDF();
        $pdf->SetAutoPageBreak(false);

        for (; ; $url->incrementImageNumber()) {

            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() !== 200) {
                break;
            }
            echo "Getting $url" . PHP_EOL;

            $img = imagecreatefromstring($response->getContent());

            $imageWithPath = $directory->getFullPath() . $url->getImageNumber() . '.jpg';
            imagejpeg($img, $imageWithPath, 100);
            $pdf->AddPage();
            $pdf->Image($imageWithPath, 0, 0, 210, 297);
        }

        ob_start();
        $pdf->Output();
        $data = ob_get_clean();

        $outputDir = new DefaultDirectory('output');
        file_put_contents($outputDir->getFullPath() . $this->outputFilename . '.pdf', $data);
    }

    /**
     * @throws BookNotFoundException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchBook(): void
    {
        $response = $this->client->request('GET', $this->url);
        if (!$this->isCorrectResponse($response)) {
            throw new BookNotFoundException();
        }
        $url = URLBuilder::build($this->url, Flip5HtmlURL::class);

        $this->fetchPage($url);
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function isCorrectResponse(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $html = $response->getContent();
        if (str_contains($html, '<title>Book not found</title>')) {
            return false;
        }

        return true;
    }
}
