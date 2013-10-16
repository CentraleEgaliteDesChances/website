<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ComposerInstallCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('integration:composer:install')
            ->setDescription('Installation des vendors par composer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Installation des vendors...</info>");
        shell_exec('php composer.phar install --optimize-autoloader --quiet');
    }
}
