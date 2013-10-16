<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenererDocumentationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('integration:generer:documentation')
            ->setDescription('Gérération de la documentation des APIs du site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO:
        // $output->writeln("<info>Génération de la documentation...</info>");
        // shell_execll_exec('');
    }
}
