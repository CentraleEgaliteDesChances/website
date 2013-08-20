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
        $today = new \DateTime();
        $seance1 = new Seance();
        $seance1->setGroupe($this->getReference('jj_premieres'))
            ->setDate(new \DateTime());
        
        $dans7Jours = new \DateTime();
        $dans7Jours->add(\DateInterval::createFromDateString('7 days'));
        $seance2 = new Seance();
        $seance2->setGroupe($this->getReference('jj_premieres'))
            ->setDate($dans7Jours);
            
        $dans3Jours = new \DateTime();
        $dans3Jours->add(\DateInterval::createFromDateString('3 days'));
        $seance3 = new Seance();
        $seance3->setGroupe($this->getReference('jj_terminales'))
            ->setDate($dans3Jours)
            ->setDebut(new \DateTime('18:30:00'))
            ->setFin(new \DateTime('21:00:00'));
                
        $seance4 = new Seance();
        $seance4->setGroupe($this->getReference('mounier_montesquieu_secondes'))
            ->setDate($dans3Jours)
            ->setDebut(new \DateTime('17:00:00'))
            ->setFin(new \DateTime('19:30:00'));
                
        $seance5 = new Seance();
        $seance5->setGroupe($this->getReference('jj_terminales_ancien'))
            ->setDate(new \DateTime('2013-04-27 17:30:00'));
        
        $manager->persist($seance1);
        $manager->persist($seance2);
        $manager->persist($seance3);
        $manager->persist($seance4);
        $manager->persist($seance5);
        $manager->flush();
        
        $this->addReference('seance1', $seance1);
        $this->addReference('seance2', $seance2);
        $this->addReference('seance3', $seance3);
        $this->addReference('seance4', $seance4);
        $this->addReference('seance5', $seance5);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 40;
    }
}
