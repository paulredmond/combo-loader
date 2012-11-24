<?php

$app = require __DIR__ . "/bootstrap.php";

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application($app['name'], $app['version']);

$console
    ->register('version')
    ->setDescription('Get application version.')
    ->setCode(
    function(InputInterface $input, OutputInterface $output) use ($app) {
        $output->write(sprintf("%s version %s\n", $app['name'], $app['version']));
    }
);

return $console;