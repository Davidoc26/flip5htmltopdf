<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf;

use Davidoc26\Flip5HtmlToPdf\Exceptions\BookNotFoundException;
use Davidoc26\Flip5HtmlToPdf\Generators\Generator;
use Davidoc26\Flip5HtmlToPdf\Urls\Builder;
use Davidoc26\Flip5HtmlToPdf\Urls\Url;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Parser
{
    private Url $url;
    private HttpClientInterface $client;

    public function __construct(string $url, private Generator $generator)
    {
        $this->url = Builder::build($url);
        $this->client = HttpClient::create();
    }

    /**
     * @throws BookNotFoundException
     */
    public function fetchBook(): void
    {
        $response = $this->client->request('GET', (string)$this->url);
        if (!$this->isCorrectResponse($response)) {
            throw new BookNotFoundException();
        }

        $this->fetchPages();
    }

    private function fetchPages(): void
    {
        $this->generator->fetch($this->url, $this->client);
    }

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
