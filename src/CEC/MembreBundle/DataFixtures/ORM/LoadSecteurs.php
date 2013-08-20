<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Secteur;

class LoadSecteurs extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
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
        
        $this->addReference('secteur_sorties', $secteurSorties);
        $this->addReference('secteur_projets', $secteurProjets);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 10;
    }
}
