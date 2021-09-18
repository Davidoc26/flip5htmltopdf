<?php

namespace Davidoc26\Flip5HtmlToPdf\Command;

use Davidoc26\Flip5HtmlToPdf\Exception\BookNotFoundException;
use Davidoc26\Flip5HtmlToPdf\Exception\IncorrectURLException;
use Davidoc26\Flip5HtmlToPdf\Flip5Html;
use Davidoc26\Flip5HtmlToPdf\Flip5HtmlCLI;
use Davidoc26\Flip5HtmlToPdf\URL\URLBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ParseCommand extends Command
{
    protected static $defaultName = 'parse';

    protected function configure()
    {
        $this->addArgument('url', InputArgument::REQUIRED, 'Flip5Html book url');
        $this->addOption(
            'filename',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Output PDF filename',
            'output'
        );
        $this->addOption(
            'temp',
            't',
            InputOption::VALUE_OPTIONAL,
            'With temp files',
            false,
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws BookNotFoundException
     * @throws IncorrectURLException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('temp') !== false) {
            $input->setOption('temp', true);
        }

        $url = $input->getArgument('url');
        $io = new SymfonyStyle($input, $output);

        $url = URLBuilder::build($url);
        if (empty($url->getHost()) || $url->getPath() === '/' || !in_array($url->getWithoutPath(), Flip5Html::ALLOWED_URLS)) {
            throw new IncorrectURLException();
        }

        $parser = new Flip5HtmlCLI($input->getArgument('url'), $input->getOption('filename'), $input->getOption('temp'));
        $parser->setOutput($output);
        $parser->fetchBook();

        $io->info('PDF has been generated!');
        return Command::SUCCESS;
    }
}
