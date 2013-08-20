<?php

namespace CEC\MembreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CEC\MembreBundle\Entity\Secteur;

class SecteursLoadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('membre:secteurs:load')
            ->setDescription("Charge les secteurs de l'association en base de donnée");
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        
        $output->writeln("<info>Suppression des secteurs...</info>");
        $secteurs = $doctrine->getRepository('CECMembreBundle:Secteur')->findAll();
        $manager = $doctrine->getEntityManager();
        foreach ($secteurs as $secteur) {
            $manager->remove($secteur);
        }
        
        $output->writeln("<info>Ajout des nouveaux secteurs...</info>");
        $secteurSorties = new Secteur();
        $secteurSorties->setNom('Secteur Sorties');
    
        $secteurProjets = new Secteur();
        $secteurProjets->setNom('Secteur Projets');
        
        $secteurEvenements = new Secteur();
        $secteurEvenements->setNom('Secteur Événements');
        
        $secteurFundraising = new Secteur();
        $secteurFundraising->setNom('Secteur Fundraising');
        
        $secteurActivitesScientifiques = new Secteur();
        $secteurActivitesScientifiques->setNom('Secteur Activités Scientifiques');
        
        $secteurActivitesCulturelles = new Secteur();
        $secteurActivitesCulturelles->setNom('Secteur Activités Culturelles');
        
        $manager->persist($secteurSorties);
        $manager->persist($secteurProjets);
        $manager->persist($secteurEvenements);
        $manager->persist($secteurFundraising);
        $manager->persist($secteurActivitesScientifiques);
        $manager->persist($secteurActivitesCulturelles);
        $manager->flush();
    }
}
