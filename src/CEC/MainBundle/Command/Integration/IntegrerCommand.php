<?php

namespace CEC\MainBundle\Command\Integration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class IntegrerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('integration:integrer')
            ->setDescription('Déploiement du site sur le serveur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<comment>Déploiement du site</comment>");
        
        $configurerLogsCommand = $this->getApplication()->find('integration:configurer:logs');
        $configurerLogsArguments = array('command' => 'integration:configurer:logs');
        $configurerLogsCommand->run(new ArrayInput($configurerLogsArguments), $output);
        
        $configurerCacheCommand = $this->getApplication()->find('integration:configurer:cache');
        $configurerCacheArguments = array('command' => 'integration:configurer:cache');
        $configurerCacheCommand->run(new ArrayInput($configurerCacheArguments), $output);
        
        $configurerParametresCommand = $this->getApplication()->find('integration:configurer:parametres');
        $configurerParametresArguments = array('command' => 'integration:configurer:parametres');
        $configurerParametresCommand->run(new ArrayInput($configurerParametresArguments), $output);
        
        $composerInstallCommand = $this->getApplication()->find('integration:composer:install');
        $composerInstallArguments = array('command' => 'integration:composer:install');
        $composerInstallCommand->run(new ArrayInput($composerInstallArguments), $output);
        
        $output->writeln("<info>Suppression du cache...</info>");
        $clearCacheCommand = $this->getApplication()->find('cache:clear');
        $clearCacheArguments = array(
            'command'    => 'cache:clear',
            '--env'      => 'prod',
            '--no-debug' => true,
        );
        $clearCacheCommand->run(new ArrayInput($clearCacheArguments), $output);
        
        $output->writeln("<info>Installation des assets...</info>");
        $installAssetsCommand = $this->getApplication()->find('assets:install');
        $installAssetsArguments = array(
            'command'    => 'assets:install',
            '--env'      => 'prod',
            '--no-debug' => true,
        );
        $installAssetsCommand->run(new ArrayInput($installAssetsArguments), $output);
    
        $output->writeln("<info>Chargement d'assetic...</info>");
        $dumpAsseticCommand = $this->getApplication()->find('assetic:dump');
        $dumpAsseticArguments = array(
            'command'    => 'assetic:dump',
            '--env'      => 'prod',
            '--no-debug' => true,
        );
        $dumpAsseticCommand->run(new ArrayInput($dumpAsseticArguments), $output);
        
        $genererDocumentationCommand = $this->getApplication()->find('integration:generer:documentation');
        $genererDocumentationArguments = array('command' => 'integration:generer:documentation');
        $genererDocumentationCommand->run(new ArrayInput($genererDocumentationArguments), $output);
        
        $configurerDocumentsCommand = $this->getApplication()->find('integration:configurer:documents');
        $configurerDocumentsArguments = array('command' => 'integration:configurer:documents');
        $configurerDocumentsCommand->run(new ArrayInput($configurerDocumentsArguments), $output);
    }
}
