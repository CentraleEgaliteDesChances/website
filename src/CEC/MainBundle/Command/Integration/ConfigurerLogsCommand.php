<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurerLogsCommand extends Command
{
    const CHEMIN_DOSSIER_LOGS = 'app/logs';

    protected function configure()
    {
        $this
            ->setName('integration:configurer:logs')
            ->setDescription('Configuration des logs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Configuration des logs...</info>");
        
        // On règle récursivement les permissions ddu dossier logs
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::CHEMIN_DOSSIER_LOGS));
        foreach ($iterator as $fichier) {
            chmod($fichier, 0777);
        }
    }
}
