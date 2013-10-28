<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurerParametresCommand extends Command
{
    const CHEMIN_PARAMETRES = 'app/config/parameters.yml';
    const CHEMIN_PARAMETRES_DEFAULT = 'app/config/parameters.yml.dist';

    protected function configure()
    {
        $this
            ->setName('integration:configurer:parametres')
            ->setDescription('Configuration du fichier de paramètres locaux');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Configuration des paramètres locaux...</info>");
        
        // Si le fichier n'existe pas, on le crée
        if (!file_exists(self::CHEMIN_PARAMETRES)) {
            copy(self::CHEMIN_PARAMETRES, self::CHEMIN_PARAMETRES_DEFAULT);
        }
    }
}
