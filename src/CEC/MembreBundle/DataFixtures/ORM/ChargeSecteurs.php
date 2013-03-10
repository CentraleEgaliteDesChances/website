<?php

namespace CEC\MembreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\MembreBundle\Entity\Secteur;

class ChargeSecteurs extends AbstractFixture implements OrderedFixtureInterface
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
        
        $secteurProjets->addMembre($this->getReference('helene_sicsic'));
        $secteurProjets->addMembre($this->getReference('pol_maire'));
        $secteurSorties->addMembre($this->getReference('pol_maire'));
        
        $manager->persist($secteurSorties);
        $manager->persist($secteurProjets);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}
