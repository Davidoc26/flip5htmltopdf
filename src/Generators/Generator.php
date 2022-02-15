<?php

declare(strict_types=1);

namespace Davidoc26\Flip5HtmlToPdf\Generators;

use Davidoc26\Flip5HtmlToPdf\Urls\Url;
use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TCPDF;
use function preg_match;

abstract class Generator
{
    protected TCPDF $pdf;

    abstract public function generate(array $responses): void;

    public function __construct(protected string $outputPath)
    {
        $this->pdf = new TCPDF();
    }

    public function fetch(Url $url, HttpClientInterface $client): void
    {
        $totalPageCount = $this->getTotalPageCount($url, $client);

        $responses = [];
        for (; $url->getImageNumber() <= $totalPageCount; $url->increment()) {
            $responses[] = $client->request('GET', $url->getIncrementedUrl());
        }

        $this->generate($responses);
    }

    protected function getTotalPageCount(Url $url, HttpClientInterface $client): int
    {
        $response = $client->request('GET', $url->getUrl());
        $pattern = '/var showbook_pages = "(.*)";/';
        preg_match($pattern, $response->getContent(), $matches);

        return (int)($matches[1] ?? throw new RuntimeException());
    }

    public function setOutputPath(string $outputPath): static
    {
        $this->outputPath = $outputPath;

        return $this;
    }
}
