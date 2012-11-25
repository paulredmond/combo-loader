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
        $output->writeln(sprintf("%s version %s", $app['name'], $app['version']));
    }
);

use Symfony\Component\Finder\Finder;

$console
    ->register('cache:clear')
    ->setDescription('Clear file cache (http and assetic).')
    ->setCode(
        function(InputInterface $input, OutputInterface $output) use ($app) {
            $output->writeln('Clearing cache...');

            $message = "<info>Deleted %s: %s</info>";
            $files = new Finder();
            $files
                ->notName('.*')
                ->in($app['combo.cache_path'])
                ->in($app['http_cache.cache_dir'])
                ->files()
            ;

            foreach ($files as $file) {
                unlink($file->getRealpath());
                $output->writeln(sprintf($message, 'file', $file->getRelativePathname()));
            }

            $output->writeln('Cache cleared...');
        }
    )
;

return $console;