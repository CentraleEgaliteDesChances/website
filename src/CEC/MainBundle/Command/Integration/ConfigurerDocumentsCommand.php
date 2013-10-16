<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurerDocumentsCommand extends Command
{
    const CHEMIN_DOSSIER_DOCUMENTS = 'web/uploads/documents';
    
    protected function configure()
    {
        $this
            ->setName('integration:configurer:documents')
            ->setDescription('Configuration du dossier contenant les documents');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Configuration du dossier Documents...</info>");
        
        // On crée le dossier s'il n'existe pas encore
        if (!file_exists(self::CHEMIN_DOSSIER_DOCUMENTS)) {
            mkdir(self::CHEMIN_DOSSIER_DOCUMENTS, 0777, true);
        }
        
        // On règle les permissions
        chmod(self::CHEMIN_DOSSIER_DOCUMENTS, 0777);
    }
}
