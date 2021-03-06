<?php

require_once 'bootstrap/bootstrap.php';

use Davidoc26\Flip5HtmlToPdf\Commands\ParseCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands([
    new ParseCommand(),
]);

$application->run();
