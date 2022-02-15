<?php

namespace Davidoc26\Flip5HtmlToPdf\Commands;

use Davidoc26\Flip5HtmlToPdf\Exceptions\BookNotFoundException;
use Davidoc26\Flip5HtmlToPdf\Generators\PdfGeneratorCLI;
use Davidoc26\Flip5HtmlToPdf\Parser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use const ROOT;

class ParseCommand extends Command
{
    protected static $defaultName = 'parse';

    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::REQUIRED, 'Flip5Html book url');
        $this->addOption(
            name: 'filename',
            shortcut: 'f',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Output PDF filename',
            default: 'output'
        );
        $this->addOption(
            name: 'path',
            shortcut: 'p',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Output path',
            default: ROOT . '/output',
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws BookNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $parser = new Parser(
            url: $input->getArgument('url'),
            generator: new PdfGeneratorCLI($input->getOption('path'), $input->getOption('filename'), $output)
        );
        $parser->fetchBook();

        return Command::SUCCESS;
    }
}
