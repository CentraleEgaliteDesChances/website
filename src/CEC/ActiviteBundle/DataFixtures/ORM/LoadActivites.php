<?php

namespace CEC\ActiviteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\ActiviteBundle\Entity\Activite;

class LoadActivites extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $maintenant = new \DateTime();
    
        $acti1 = new Activite();
        $acti1->setTitre("Activité 1")
              ->setDescription("Cette activité consiste en un data fixture permettant de tester le site.")
              ->setDuree("Entre 45 et 90 minutes")
              ->setType("Activité Culturelle")
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant);
        
        $acti2 = new Activite();
        $acti2->setTitre("Activité 2")
              ->setDescription("De la même façon, cette activité n'est qu'un test.")
              ->setDuree("1 heure")
              ->setType("Expérience Scientifique")
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant);
        
        $manager->persist($acti1);
        $manager->persist($acti2);
        $manager->flush();
        
        $this->addReference('acti1', $acti1);
        $this->addReference('acti2', $acti2);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 7;
    }
}
