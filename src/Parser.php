<?php

namespace Davidoc26\Flip5HtmlToPdf;

use Davidoc26\Flip5HtmlToPdf\Directory\TempDirectory;
use Davidoc26\Flip5HtmlToPdf\Exception\BookNotFoundException;
use Davidoc26\Flip5HtmlToPdf\URL\Flip5HtmlURL;
use Davidoc26\Flip5HtmlToPdf\URL\URLBuilder;
use FPDF;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class Parser
{
    protected HttpClientInterface $client;
    protected string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->client = HttpClient::create();
        $this->url = $url;
    }

    public const ALLOWED_URLS = [
        'https://fliphtml5.com/',
        'https://online.fliphtml5.com/',
    ];

    /**
     * @param Flip5HtmlURL $url
     */
    abstract protected function fetchPages(Flip5HtmlURL $url): void;

    /**
     * @param Flip5HtmlURL $url
     * @param FPDF $pdf
     * @param TempDirectory $directory
     * @param OutputInterface|null $output
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getPagesToPdf(Flip5HtmlURL $url, FPDF $pdf, TempDirectory $directory, ?OutputInterface $output): void
    {
        for (; ; $url->incrementImageNumber()) {
            $response = $this->client->request('GET', $url);
            if ($response->getStatusCode() !== 200) {
                break;
            }
            $output?->writeln("Getting $url");

            $img = imagecreatefromstring($response->getContent());

            $imageWithPath = $directory->getFullPath() . $url->getImageNumber() . '.jpg';
            imagejpeg($img, $imageWithPath, 100);
            $pdf->AddPage();
            $pdf->Image($imageWithPath, 0, 0, 210, 297);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function isCorrectResponse(ResponseInterface $response): bool
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

        $this->fetchPages($url);
    }
}
