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
              ->addTag($this->getReference('tag_premieres'))
              ->addTag($this->getReference('tag_terminales'))
              ->addTag($this->getReference('tag_equations_differentielles'))
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant);
              
        $this->getReference('seance1')->addActivite($acti1);
        $this->getReference('seance4')->addActivite($acti1);
                  
        $acti2 = new Activite();
        $acti2->setTitre("Activité 2")
              ->setDescription("De la même façon, cette activité n'est qu'un test.")
              ->setDuree("1 heure")
              ->setType("Expérience Scientifique")
              ->addTag($this->getReference('tag_premieres'))
              ->addTag($this->getReference('tag_expression_orale'))
              ->addTag($this->getReference('tag_culture_generale'))
              ->addTag($this->getReference('tag_terminales'))
              ->setDateCreation($maintenant)
              ->setDateModification($maintenant);
              
        $this->getReference('seance5')->addActivite($acti2);
        
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
        return 70;
    }
}
