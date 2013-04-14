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
        
        $manager->persist($secteurSorties);
        $manager->persist($secteurProjets);
        $manager->flush();
        
        $this->addReference('secteur_sorties', $secteurSorties);
        $this->addReference('secteur_projets', $secteurProjets);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 1;
    }
}
