<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurerCacheCommand extends Command
{
    const CHEMIN_DOSSIER_CACHE = 'app/cache';

    protected function configure()
    {
        $this
            ->setName('integration:configurer:cache')
            ->setDescription('Configuration du cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Configuration du cache...</info>");
        
        // On règle récursivement les permissions du cache
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::CHEMIN_DOSSIER_CACHE));
        foreach ($iterator as $fichier) {
            chmod($fichier, 0777);
        }
    }
}
