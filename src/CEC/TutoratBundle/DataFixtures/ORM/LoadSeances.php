<?php

namespace CEC\TutoratBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CEC\TutoratBundle\Entity\Seance;


class LoadSeances extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $seance1 = new Seance();
        $seance1->setGroupe($this->getReference('jj_premieres'))
            ->setDebut(new \DateTime('2013-03-12 17:30:00'));
            
        $seance2 = new Seance();
        $seance2->setGroupe($this->getReference('jj_premieres'))
            ->setDebut(new \DateTime('2013-12-17 17:30:00'));
            
        $seance3 = new Seance();
        $seance3->setGroupe($this->getReference('jj_terminales'))
            ->setDebut(new \DateTime('2013-03-12 18:30:00'))
            ->setFin(new \DateTime('2013-03-12 20:30:00'));
                
        $seance4 = new Seance();
        $seance4->setGroupe($this->getReference('mounier_montesquieu_secondes'))
            ->setDebut(new \DateTime('2013-11-12 18:00:00'));
                
        $seance5 = new Seance();
        $seance5->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setDebut(new \DateTime('2012-12-17 17:30:00'));
        
        $manager->persist($seance1);
        $manager->persist($seance2);
        $manager->persist($seance3);
        $manager->persist($seance4);
        $manager->persist($seance5);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 4;
    }
}
